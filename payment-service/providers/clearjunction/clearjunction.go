package clearjunction

import (
	"bytes"
	"crypto/sha512"
	"encoding/json"
	"fmt"
	"net/http"
	"payment-service/providers"
	"strings"
	"time"
)

var _ providers.PaymentProvider = (*ClearJunction)(nil)

func NewClearJunction(apiKey, password string, baseURL string) *ClearJunction {
	return &ClearJunction{
		APIKey:     apiKey,
		Password:   password,
		BaseURL:    baseURL,
		httpClient: &http.Client{},
		headers:    make(map[string]string),
	}
}

func (cj *ClearJunction) Auth(request providers.AuthRequester) (providers.AuthResponder, error) {
	// Реализуйте метод Auth здесь
	return nil, nil
}

func (cj *ClearJunction) SetAuthHeaders(body []byte) {
	// Получаем текущее время и форматируем его в соответствии с требуемым форматом
	date := time.Now().UTC().Format("2006-01-02T15:04:05.000Z")

	// Создаем подпись, используя пароль, и преобразуем ее в верхний регистр
	signatureData := []byte(fmt.Sprintf("%s", cj.Password))
	signature := strings.ToUpper(fmt.Sprintf("%x", sha512.Sum512(signatureData)))

	// Создаем новую подпись, используя API-ключ, дату и подпись, полученную на предыдущем шаге
	// К тому же добавляем тело запроса, преобразуя его в верхний регистр
	signatureData = []byte(fmt.Sprint(strings.ToUpper(cj.APIKey), date, signature))
	signatureData = append(signatureData, bytes.ToUpper(body)...)
	signature = fmt.Sprintf("%x", sha512.Sum512(signatureData))

	// Создаем значение заголовка авторизации, используя полученную подпись
	authHeaderValue := fmt.Sprintf("Bearer %s", signature)

	// Устанавливаем заголовки для последующих запросов
	cj.headers["Authorization"] = authHeaderValue
	cj.headers["X-API-KEY"] = cj.APIKey
	cj.headers["Content-Type"] = "application/json"
	cj.headers["Date"] = date
}

func (cj *ClearJunction) IBAN(request providers.IBANRequester) (providers.IBANResponder, error) {
	ibanRequest, _ := request.(IBANRequest)

	url := fmt.Sprintf("%sv7/gate/allocate/v2/list/iban/%s", cj.BaseURL, ibanRequest.ClientCustomerId)

	req, err := http.NewRequest(http.MethodGet, url, nil)
	if err != nil {
		return nil, fmt.Errorf("failed to create IBAN request: %v", err)
	}

	for key, value := range cj.headers {
		req.Header[key] = []string{value}
	}

	client := http.Client{}
	res, err := client.Do(req)
	if err != nil {
		return nil, fmt.Errorf("failed to send IBAN request: %v", err)
	}
	defer res.Body.Close()

	if res.StatusCode != http.StatusOK {
		return nil, fmt.Errorf("IBAN request failed with status code: %d", res.StatusCode)
	}

	var ibanResponse IBANResponse
	err = json.NewDecoder(res.Body).Decode(&ibanResponse)
	if err != nil {
		return nil, fmt.Errorf("failed to unmarshal IBAN response: %v", err)
	}

	return &ibanResponse, nil
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
	//TODO implement me
	panic("implement me")
}

func (cj *ClearJunction) PostBack(request providers.PostBackRequester) (providers.PostBackResponder, error) {
	//TODO implement me
	panic("implement me")
}
