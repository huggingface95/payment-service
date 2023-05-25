package clearjunction

import (
	"bytes"
	"crypto/sha512"
	"encoding/json"
	"fmt"
	"github.com/valyala/fasthttp"
	"payment-service/db"
	"payment-service/providers"
	"payment-service/queue"
	"payment-service/utils"
	"strings"
	"time"
)

var _ providers.PaymentProvider = (*ClearJunction)(nil)
var _ PaymentProvider = (*ClearJunction)(nil)

func New(services Services, apiKey, password, baseURL string) *ClearJunction {
	provider := &ClearJunction{
		APIKey:   apiKey,
		Password: password,
		BaseURL:  baseURL,

		Services:  services,
		transport: utils.FastHTTP{Client: &fasthttp.Client{}, ReqHeaders: map[string]string{}},
	}

	provider.Auth(providers.AuthRequester(AuthRequest{}))

	services.Providers.ProvidersList[utils.GetCurrentPackageName()] = provider
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
	ibanRequest, ok := request.(map[string]interface{})
	if !ok {
		return nil, fmt.Errorf("invalid IBAN request")
	}

	url := fmt.Sprintf("%sv7/gate/allocate/v2/iban", cj.BaseURL)

	// Выполнение POST запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cj.transport.Request("POST", url, ibanRequest)
	if err != nil {
		return nil, fmt.Errorf("failed to send IBAN request: %w", err)
	}

	// Преобразование тела ответа в структуру IBANResponse
	var ibanResponse IBANResponse
	err = json.Unmarshal(responseBody, &ibanResponse)
	if err != nil {
		return nil, fmt.Errorf("failed to unmarshal IBAN response: %w", err)
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
	statusRequest, ok := request.(StatusRequest)
	if !ok {
		return nil, fmt.Errorf("invalid request type")
	}

	url := fmt.Sprintf("%sv7/gate/allocate/v2/list/iban/%s", cj.BaseURL, statusRequest.ClientCustomerId)

	// Выполнение GET запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cj.transport.Request(fasthttp.MethodGet, url, nil)
	if err != nil {
		return nil, fmt.Errorf("failed to send Status request: %w", err)
	}

	// Преобразование тела ответа в структуру StatusResponse
	var statusResponse StatusResponse
	err = json.Unmarshal(responseBody, &statusResponse)
	if err != nil {
		return nil, fmt.Errorf("failed to unmarshal Status response: %w", err)
	}

	return statusResponse, nil
}

func (cj *ClearJunction) PostBack(request providers.PostBackRequester) (providers.PostBackResponder, error) {
	switch req := request.(type) {
	case *IbanPostbackRequest:
		return cj.handleIbanPostback(req)
	case *PayPostbackRequest:
		return cj.handlePayPostback(req)
	default:
		return nil, fmt.Errorf("unsupported postback request type")
	}
}

func (cj *ClearJunction) handleIbanPostback(request *IbanPostbackRequest) (providers.PostBackResponder, error) {
	if request.Status == StatusAccepted {
		// Формируем map с полями для обновления и условиями where и передаём в функцию обновления таблицы
		if err := cj.Services.DB.Pg.Update("accounts",
			map[string]interface{}{"account_state_id": db.AccountStateWaitingForApproval, "account_number": request.Iban},
			map[string]interface{}{"order_reference": request.OrderReference},
		); err != nil {
			return nil, fmt.Errorf("failed to update account: %w", err)
		}

		return &IbanPostbackResponse{OrderReference: request.OrderReference}, nil
	}

	return nil, fmt.Errorf("wrong request")
}

func (cj *ClearJunction) handlePayPostback(request *PayPostbackRequest) (providers.PostBackResponder, error) {
	payment, err := cj.Services.DB.Pg.GetPaymentWithRelations(
		[]string{"Status", "Provider", "OperationType"},
		map[string]interface{}{"payment_number": request.OrderReference},
	)
	if err != nil {
		return nil, fmt.Errorf("failed to get payment: %w", err)
	}

	if request.Status != payment.Status.Name {
		if err := cj.Services.DB.Pg.Update("payment",
			map[string]interface{}{"amount": request.Amount, "status_id": db.GetStatus(request.Status)},
			map[string]interface{}{"payment_number": request.OrderReference},
		); err != nil {
			return nil, fmt.Errorf("failed to update payment: %w", err)
		}

		if request.Status == "completed" {
			result, err := cj.payoutApprove(request.OrderReference)
			if err != nil {
				return nil, err
			}

			if len(result.Messages) > 0 {
				return nil, nil
			}

			var nextBalance = float64(0)
			if payment.OperationTypeId == db.OperationTypeIncoming {
				nextBalance = payment.Account.CurrentBalance + request.Amount
			} else {
				nextBalance = payment.Account.CurrentBalance - request.Amount
			}

			if _, err = cj.Services.DB.Pg.Insert("transaction", map[string]interface{}{
				"payment_id":   payment.ID,
				"amount":       request.Amount,
				"balance_prev": payment.Account.CurrentBalance,
				"balance_next": nextBalance,
			}); err != nil {
				return nil, err
			}

			if err := cj.Services.DB.Pg.Update("account",
				map[string]interface{}{"current_balance": nextBalance},
				map[string]interface{}{"id": payment.Account.ID},
			); err != nil {
				return nil, err
			}
		}

		payload, err := json.Marshal(queue.EmailPayload{
			ID:      int64(payment.ID),
			Status:  request.Status,
			Message: "Payment postback status",
			Data:    request,
		})
		if err != nil {
			return nil, err
		}
		if err := queue.PublishMessage(cj.Services.Queue, &queue.Task{
			Type:     "email",
			Payload:  payload,
			Provider: "clearjunction",
		}); err != nil {
			return nil, err
		}

		return &PayPostbackResponse{OrderReference: request.OrderReference}, nil
	}

	return nil, fmt.Errorf("wrong request")
}

func (cj *ClearJunction) payoutApprove(orderReference string) (result PayoutApproveResponse, err error) {
	params := map[string]interface{}{
		"orderReferenceArray": []string{orderReference},
	}

	responseData, err := cj.transport.Request("POST", "gate/transactionAction/approve", params)
	if err != nil {
		return result, err
	}

	response := &PayoutApproveResponseWrapper{}
	err = json.Unmarshal(responseData, response)
	if err != nil {
		return result, err
	}

	if len(response.ActionResult) > 0 {
		result = response.ActionResult[0]
	} else {
		err = fmt.Errorf("empty action result")
	}

	return result, err
}
