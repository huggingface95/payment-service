package currencycloud

import (
	"context"
	"encoding/csv"
	"encoding/json"
	"fmt"
	"github.com/fatih/structs"
	"github.com/gofiber/fiber/v2"
	"github.com/valyala/fasthttp"
	"golang.org/x/time/rate"
	"os"
	"path/filepath"
	"payment-service/providers"
	"payment-service/utils"
	"runtime"
	"sync"
)

var _ providers.PaymentProvider = (*CurrencyCloud)(nil)
var _ PaymentProvider = (*CurrencyCloud)(nil)

func New(services Services, loginID, apiKey, baseURL, publicURL string) *CurrencyCloud {
	provider := &CurrencyCloud{
		LoginID:   loginID,
		APIKey:    apiKey,
		BaseURL:   baseURL,
		PublicURL: publicURL,
		Services:  services,
		transport: utils.FastHTTP{Client: &fasthttp.Client{}, ReqHeaders: sync.Map{}},
	}

	fmt.Println(provider.BaseURL)

	services.Providers.ProvidersList[utils.GetCurrentPackageName()] = provider
	return provider
}

func (cc *CurrencyCloud) Auth(req providers.AuthRequester) (providers.AuthResponder, error) {
	token, ok := cc.transport.ReqHeaders.Load("X-Auth-Token")
	if ok && token != nil && token != "" {
		return AuthResponse{AuthToken: token.(string)}, nil
	}

	// Создание запроса на авторизацию
	assertedReq, ok := req.(AuthRequest)
	if !ok {
		return nil, fmt.Errorf("invalid request type")
	}

	reqURL := fmt.Sprintf("%sv2/authenticate/api", cc.BaseURL)

	// Отправка запроса на авторизацию
	responseBody, err := cc.transport.Request(fiber.MethodPost, reqURL, assertedReq, nil)
	if err != nil {
		return "", fmt.Errorf("ошибка выполнения запроса на авторизацию: %w", err)
	}

	// Распаковка данных ответа в структуру
	var authResponse AuthResponse
	err = json.Unmarshal(responseBody, &authResponse)
	if err != nil {
		return "", fmt.Errorf("ошибка распаковки данных ответа на авторизацию: %w", err)
	}

	// Обновление аутентификационного токена в ReqHeaders с использованием sync.Map
	cc.transport.ReqHeaders.Store("X-Auth-Token", authResponse.AuthToken)

	// Возвращение аутентификационного токена
	return authResponse, nil
}

func (cc *CurrencyCloud) Status(req providers.StatusRequester) (providers.StatusResponder, error) {
	assertedReq, ok := req.(StatusRequest)
	if !ok {
		return nil, fmt.Errorf("invalid request type")
	}

	reqURL := fmt.Sprintf("%sv2/payments/%s", cc.BaseURL, assertedReq.PaymentID)

	// Выполнение GET запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cc.transport.Request(fasthttp.MethodGet, reqURL, nil, cc.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("failed to send Status request: %w", err)
	}

	// Преобразование тела ответа в структуру StatusResponse
	var res StatusResponse
	err = json.Unmarshal(responseBody, &res)
	if err != nil {
		return nil, fmt.Errorf("failed to unmarshal Status response: %w", err)
	}

	return res, nil
}

func (cc *CurrencyCloud) IBAN(req providers.IBANRequester) (providers.IBANResponder, error) {
	assertedReq := req.(IBANRequest)

	reqURL := fmt.Sprintf("%sv2/beneficiaries/create", cc.BaseURL)

	// Выполнение POST запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cc.transport.Request(fasthttp.MethodPost, reqURL, assertedReq, cc.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("failed to send IBAN request: %w", err)
	}

	// Преобразование тела ответа в структуру IBANResponse
	var res IBANResponse
	err = json.Unmarshal(responseBody, &res)
	if err != nil {
		return nil, fmt.Errorf("failed to unmarshal IBAN response: %w", err)
	}

	return &res, nil
}

func (cc *CurrencyCloud) PayIn(req providers.PayInRequester) (providers.PayInResponder, error) {
	assertedReq := req.(PayInRequest)

	// Формирование URL для запроса
	reqURL := fmt.Sprintf("%s/v7/gate/invoice/creditCard", cc.BaseURL)

	// Выполнение запроса с помощью метода Request из пакета utils
	responseBody, err := cc.transport.Request(fasthttp.MethodPost, reqURL, assertedReq, cc.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("ошибка выполнения запроса: %w", err)
	}

	// Разбор ответа
	var res providers.PayInResponder
	err = json.Unmarshal(responseBody, &res)
	if err != nil {
		return nil, fmt.Errorf("ошибка разбора ответа: %w", err)
	}

	return res, nil
}

func (cc *CurrencyCloud) PayOut(req providers.PayOutRequester) (providers.PayOutResponder, error) {
	assertedReq := req.(PayOutRequest)

	// Формирование URL для запроса
	reqURL := fmt.Sprintf("%sv2/payments/create", cc.BaseURL)

	// Выполнение запроса с помощью метода Request из пакета utils
	responseBody, err := cc.transport.Request(fasthttp.MethodPost, reqURL, assertedReq, cc.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("ошибка выполнения запроса: %w", err)
	}

	// Разбор ответа
	var res providers.PayOutResponder
	err = json.Unmarshal(responseBody, &res)
	if err != nil {
		return nil, fmt.Errorf("ошибка разбора ответа: %w", err)
	}

	return res, nil
}

func (cc *CurrencyCloud) PostBack(req providers.PostBackRequester) (providers.PostBackResponder, error) {
	fmt.Printf("postback received: %v", req.(*PostbackRequest))

	return &PostbackResponse{Status: "success"}, nil
}

func (cc *CurrencyCloud) Custom(req providers.CustomRequester) (providers.CustomResponder, error) {
	switch assertedReq := req.(type) {
	case *RatesRequest:
		return cc.rates(assertedReq)
	case *RatesImportRequest:
		return cc.ratesImport(assertedReq)
	case *ConvertRequest:
		return cc.convert(assertedReq)
	default:
		return nil, fmt.Errorf("unsupported custom request type: %v", assertedReq)
	}
}

func (cc *CurrencyCloud) rates(req *RatesRequest) (providers.CustomResponder, error) {
	reqURL := fmt.Sprintf("%sv2/rates/detailed", cc.BaseURL)

	// Выполнение GET запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cc.transport.Request(fasthttp.MethodGet, reqURL, req, cc.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("failed to send Rates request: %w", err)
	}

	// Преобразование тела ответа в структуру RatesResponse
	var res RatesResponse
	err = json.Unmarshal(responseBody, &res)
	if err != nil {
		return nil, fmt.Errorf("failed to unmarshal Rates response: %w", err)
	}

	return &res, nil
}

func (cc *CurrencyCloud) ratesImport(req providers.CustomRequester) (providers.CustomResponder, error) {
	reqURL := fmt.Sprintf("%sv2/rates/find", cc.BaseURL)

	// Открываем CSV файл для чтения
	file, err := os.Open("../api/data/currency_codes.csv")
	if err != nil {
		fmt.Printf("Ошибка при открытии файла: %v\n", err)
		return nil, err
	}
	defer file.Close()

	// Создаем новый CSV Reader
	reader := csv.NewReader(file)

	// Читаем все строки из файла
	rows, err := reader.ReadAll()
	if err != nil {
		fmt.Printf("Ошибка при чтении файла: %v\n", err)
		return nil, err
	}

	// Определяем ограничение по скорости (60 запросов в минуту)
	limiter := rate.NewLimiter(150, 1)

	// Создаем список для результатов
	var currencyCodes []string

	// Выполняем запросы последовательно
	for _, row := range rows {
		if len(row) == 3 {
			// Извлекаем код валюты из второго столбца
			currencyPair := fmt.Sprintf("USD%s", row[1])

			// Ожидаем до получения разрешения на выполнение запроса
			err := limiter.Wait(context.Background())
			if err != nil {
				fmt.Printf("failed to wait for rate limit: %v\n", err)
				continue
			}

			// Выполняем GET запрос с помощью HTTP клиента API сервиса
			responseBody, err := cc.transport.Request(fasthttp.MethodGet, reqURL, RatesImportRequest{CurrencyPair: currencyPair}, cc.authMiddleware)

			if err != nil {
				fmt.Printf("failed to send Rates request: %v\n", err)
				continue
			}

			var res RatesImportResponse
			err = json.Unmarshal(responseBody, &res)
			if err != nil {
				fmt.Printf("failed to decode Rates response: %v\n", err)
				continue
			}

			if _, ok := res.Rates[currencyPair]; !ok {
				fmt.Printf("no results for currency pair: %s, unavailable list: %v\n", currencyPair, res.Unavailable)
				continue
			}

			currencyCodes = append(currencyCodes, row[1])
		}
	}

	// Создаем и возвращаем результат
	res := struct{ List []string }{
		List: currencyCodes,
	}
	return &res, nil
}

func (cc *CurrencyCloud) convert(request *ConvertRequest) (providers.CustomResponder, error) {
	reqURL := fmt.Sprintf("%sv2/conversions/create", cc.BaseURL)

	// Выполнение GET запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cc.transport.Request(fasthttp.MethodPost, reqURL, request, cc.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("failed to send Convert request: %w", err)
	}

	// Преобразование тела ответа в структуру ConvertResponse
	var res ConvertResponse
	err = json.Unmarshal(responseBody, &res)
	if err != nil {
		return nil, fmt.Errorf("failed to unmarshal Convert response: %w", err)
	}

	return &res, nil
}

func (cc *CurrencyCloud) account(req *AccountRequest) (*AccountResponse, error) {
	reqURL := fmt.Sprintf("%sv2/accounts/create", cc.BaseURL)

	// Выполнение POST запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cc.transport.Request(fasthttp.MethodPost, reqURL, structs.Map(req), cc.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("failed to send IBAN request: %w", err)
	}

	// Преобразование тела ответа в структуру BeneficiaryResponse
	var res AccountResponse
	err = json.Unmarshal(responseBody, &res)
	if err != nil {
		return nil, fmt.Errorf("failed to unmarshal Beneficiary response: %w", err)
	}

	return &res, nil
}

func (cc *CurrencyCloud) authMiddleware(requestBody []byte) (err error) {
	_, err = cc.Auth(AuthRequest{cc.LoginID, cc.APIKey})
	return
}

func GetName() string {
	_, fullPath, _, _ := runtime.Caller(0)
	return filepath.Base(filepath.Dir(fullPath))
}
