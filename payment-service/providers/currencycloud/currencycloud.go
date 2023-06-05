package currencycloud

import (
	"encoding/json"
	"fmt"
	"github.com/fatih/structs"
	"github.com/gofiber/fiber/v2"
	"github.com/valyala/fasthttp"
	"payment-service/providers"
	"payment-service/utils"
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
		transport: utils.FastHTTP{Client: &fasthttp.Client{}, ReqHeaders: map[string]string{}},
	}

	fmt.Println(provider.BaseURL)

	services.Providers.ProvidersList[utils.GetCurrentPackageName()] = provider
	return provider
}

func (cc *CurrencyCloud) Auth(request providers.AuthRequester) (providers.AuthResponder, error) {
	reqURL := fmt.Sprintf("%sv2/authenticate/api", cc.BaseURL)

	// Создание запроса на авторизацию
	assertedReq, ok := request.(AuthRequest)
	if !ok {
		return nil, fmt.Errorf("invalid request type")
	}

	// Отправка запроса на авторизацию
	var params map[string]interface{}
	paramsData, err := json.Marshal(assertedReq)
	if err != nil {
		return "", fmt.Errorf("ошибка парсинга параметров запроса на авторизацию: %w", err)
	}
	if err := json.Unmarshal(paramsData, &params); err != nil {
		return "", fmt.Errorf("ошибка парсинга параметров запроса на авторизацию: %w", err)
	}
	responseBody, err := cc.transport.Request(fiber.MethodPost, reqURL, params, nil)
	if err != nil {
		return "", fmt.Errorf("ошибка выполнения запроса на авторизацию: %w", err)
	}

	// Распаковка данных ответа в структуру
	var authResponse AuthResponse
	err = json.Unmarshal(responseBody, &authResponse)
	if err != nil {
		return "", fmt.Errorf("ошибка распаковки данных ответа на авторизацию: %w", err)
	}

	cc.transport.ReqHeaders["X-Auth-Token"] = authResponse.AuthToken

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

func (cc *CurrencyCloud) PostBack(request providers.PostBackRequester) (providers.PostBackResponder, error) {
	fmt.Printf("postback received: %v", request.(*PostbackRequest))

	return &PostbackResponse{Status: "success"}, nil
}

func (cc *CurrencyCloud) authMiddleware(requestBody []byte) (err error) {
	_, err = cc.Auth(AuthRequest{cc.LoginID, cc.APIKey})
	return
}

func (cc *CurrencyCloud) account(req *AccountRequest) (providers.IBANResponder, error) {
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
