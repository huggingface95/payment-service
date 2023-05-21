package clearjunction

import (
	"bytes"
	"crypto/sha512"
	"encoding/json"
	"fmt"
	"github.com/valyala/fasthttp"
	"payment-service/providers"
	"payment-service/utils"
	"strings"
	"time"
)

var _ providers.PaymentProvider = (*ClearJunction)(nil)

func New(service *providers.Service, apiKey, password, baseURL string) *ClearJunction {
	provider := &ClearJunction{
		transport: utils.FastHTTP{Client: &fasthttp.Client{}, ReqHeaders: map[string]string{}},
		APIKey:    apiKey,
		Password:  password,
		BaseURL:   baseURL,
	}

	provider.Auth(providers.AuthRequester(AuthRequest{}))

	service.Providers[utils.GetCurrentPackageName()] = provider
	return provider
}

func (cj *ClearJunction) Auth(request providers.AuthRequester) (providers.AuthResponder, error) {
	authRequest, _ := request.(AuthRequest)

	// Получаем текущее время и форматируем его в соответствии с требуемым форматом
	date := time.Now().UTC().Format("2006-01-02T15:04:05.000Z")

	// Создаем подпись, используя пароль, и преобразуем ее в верхний регистр
	signatureData := []byte(fmt.Sprintf("%s", cj.Password))
	signature := strings.ToUpper(fmt.Sprintf("%x", sha512.Sum512(signatureData)))

	// Создаем новую подпись, используя API-ключ, дату и подпись, полученную на предыдущем шаге
	// К тому же добавляем тело запроса, преобразуя его в верхний регистр
	signatureData = []byte(fmt.Sprint(strings.ToUpper(cj.APIKey), date, signature))
	signatureData = append(signatureData, bytes.ToUpper(authRequest.Body)...)
	signature = fmt.Sprintf("%x", sha512.Sum512(signatureData))

	// Создаем значение заголовка авторизации, используя полученную подпись
	authHeaderValue := fmt.Sprintf("Bearer %s", signature)

	// Устанавливаем заголовки для последующих запросов
	cj.transport.ReqHeaders["Authorization"] = authHeaderValue
	cj.transport.ReqHeaders["X-API-KEY"] = cj.APIKey
	cj.transport.ReqHeaders["Content-Type"] = "application/json"
	cj.transport.ReqHeaders["Date"] = date

	return nil, nil
}

func (cj *ClearJunction) IBAN(request providers.IBANRequester) (providers.IBANResponder, error) {
	//TODO implement me
	panic("implement me")
}

func (cj *ClearJunction) PayIn(request providers.PayInRequester) (providers.PayInResponder, error) {
	//TODO implement me
	panic("implement me")
}

func (cj *ClearJunction) PayOut(request providers.PayOutRequester) (providers.PayOutResponder, error) {
	//TODO implement me
	panic("implement me")
}

func (cj *ClearJunction) Status(request providers.StatusRequester) (providers.StatusResponder, error) {
	statusRequest, _ := request.(StatusRequest)

	url := fmt.Sprintf("%sv7/gate/allocate/v2/list/iban/%s", cj.BaseURL, statusRequest.ClientCustomerId)

	// Выполнение GET запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cj.transport.Request(fasthttp.MethodGet, url, nil)
	if err != nil {
		return nil, fmt.Errorf("failed to send IBAN request: %w", err)
	}

	// Преобразование тела ответа в структуру StatusResponse
	var statusResponse StatusResponse
	err = json.Unmarshal(responseBody, &statusResponse)
	if err != nil {
		return nil, fmt.Errorf("failed to unmarshal Status response: %w", err)
	}

	return &statusResponse, nil
}

func (cj *ClearJunction) PostBack(request providers.PostBackRequester) (providers.PostBackResponder, error) {
	//TODO implement me
	panic("implement me")
}
