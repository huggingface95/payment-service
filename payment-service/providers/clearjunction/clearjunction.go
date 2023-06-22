package clearjunction

import (
	"bytes"
	"crypto/sha512"
	"encoding/json"
	"fmt"
	"github.com/valyala/fasthttp"
	"path/filepath"
	"payment-service/db"
	"payment-service/providers"
	"payment-service/queue"
	"payment-service/utils"
	"runtime"
	"strings"
	"time"
)

var _ providers.PaymentProvider = (*ClearJunction)(nil)
var _ PaymentProvider = (*ClearJunction)(nil)

func New(services Services, apiKey, password, baseURL, publicURL string) *ClearJunction {
	provider := &ClearJunction{
		APIKey:    apiKey,
		Password:  password,
		BaseURL:   baseURL,
		PublicURL: publicURL,
		Services:  services,
		transport: utils.FastHTTP{Client: &fasthttp.Client{}, ReqHeaders: map[string]string{}},
	}

	services.Providers.ProvidersList[utils.GetCurrentPackageName()] = provider
	return provider
}

func (cj *ClearJunction) Auth(req providers.AuthRequester) (providers.AuthResponder, error) {
	assertedReq, ok := req.(AuthRequest)
	if !ok {
		return nil, fmt.Errorf("invalid request type")
	}

	// Получаем текущее время и форматируем его в соответствии с требуемым форматом
	date := time.Now().UTC().Format("2006-01-02T15:04:05.000Z")

	// Создаем подпись, используя пароль, и преобразуем ее в верхний регистр
	signatureData := []byte(fmt.Sprintf("%s", cj.Password))
	signature := strings.ToUpper(fmt.Sprintf("%x", sha512.Sum512(signatureData)))

	// Создаем новую подпись, используя API-ключ, дату и подпись, полученную на предыдущем шаге
	// К тому же добавляем тело запроса, преобразуя его в верхний регистр
	signatureData = []byte(fmt.Sprint(strings.ToUpper(cj.APIKey), date, signature))
	signatureData = append(signatureData, bytes.ToUpper(assertedReq.Body)...)
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

func (cj *ClearJunction) IBAN(req providers.IBANRequester) (providers.IBANResponder, error) {
	payload, ok := req.(queue.IBANPayload)
	if !ok {
		return nil, fmt.Errorf("invalid request type")
	}

	postbackURL := cj.PublicURL + "clearjunction/iban/postback"

	request := IBANRequest{
		ClientOrder: GenerateClientOrder(),
		PostbackURL: &postbackURL,
		Registrant: Registrant{
			ClientCustomerID: fmt.Sprintf("%d", payload.AccountID),
		},
	}

	switch payload.AccountType {
	case queue.AccountTypePrivate:
		applicant := payload.ApplicantIndividual
		request.Registrant.Individual = &IndividualData{
			Phone:      applicant.Phone,
			Email:      applicant.Email,
			BirthDate:  applicant.BirthAt,
			BirthPlace: applicant.BirthPlace,
			Address:    AddressData(applicant.Address),
			Document: DocumentData{
				Type:              DocumentTypeEnum(applicant.Document.Type),
				Number:            applicant.Document.Number,
				IssuedCountryCode: applicant.Document.IssuedCountryCode,
				IssuedBy:          applicant.Document.IssuedBy,
				IssuedDate:        applicant.Document.IssuedDate,
				ExpirationDate:    applicant.Document.ExpirationDate,
			},
			LastName:   applicant.LastName,
			FirstName:  applicant.FirstName,
			MiddleName: applicant.MiddleName,
		}
	case queue.AccountTypeBusiness:
		applicant := payload.ApplicantCompany
		request.Registrant.Corporate = &CorporateData{
			Email:                   applicant.Email,
			Name:                    "",
			RegistrationNumber:      "",
			IncorporationCountry:    "",
			Address:                 Address{},
			IncorporationDate:       utils.ISOExtendedDate{},
			UltimateBeneficialOwner: nil,
			TradingWebsite:          nil,
			ExpectedTurnover:        0,
			BeneficialLegalEntity:   nil,
			OtherDetails:            OtherDetails{},
			BusinessPartners:        nil,
			FundFlows:               FundFlows{},
			ComplianceEvaluation:    ComplianceEvaluation{},
			CustomOptions:           nil,
		}
	default:
		return nil, fmt.Errorf("unknown account type")
	}

	reqURL := fmt.Sprintf("%sv7/gate/allocate/v2/create/iban", cj.BaseURL)

	// Выполнение POST запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cj.transport.Request(fasthttp.MethodPost, reqURL, request, cj.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("failed to send IBAN request: %w", err)
	}

	// Преобразование тела ответа в структуру IBANResponse
	var res IBANResponse
	if err := json.Unmarshal(responseBody, &res); err != nil {
		return nil, fmt.Errorf("failed to unmarshal IBAN response: %w", err)
	}

	if res.Status != "accepted" {
		return nil, fmt.Errorf("wrong status for IBAN response: %s", res.Status)
	}

	err = cj.Services.DB.Pg.SetAccountOrderReferenceAndStateToWaitingForApproval(payload.AccountID, res.OrderReference)
	if err != nil {
		return nil, err
	}

	return &res, nil
}

func (cj *ClearJunction) PayIn(req providers.PayInRequester) (providers.PayInResponder, error) {
	assertedReq, ok := req.(map[string]interface{})
	if !ok {
		return nil, fmt.Errorf("invalid request type")
	}

	// Формирование URL для запроса
	reqURL := fmt.Sprintf("%s/v7/gate/invoice/creditCard", cj.BaseURL)

	// Выполнение запроса с помощью метода Request из пакета utils
	responseBody, err := cj.transport.Request(fasthttp.MethodPost, reqURL, assertedReq, cj.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("ошибка выполнения запроса: %w", err)
	}

	// Разбор ответа
	var res providers.PayInResponder
	if err := json.Unmarshal(responseBody, &res); err != nil {
		return nil, fmt.Errorf("ошибка разбора ответа: %w", err)
	}

	return res, nil
}

func (cj *ClearJunction) PayOut(req providers.PayOutRequester) (providers.PayOutResponder, error) {
	assertedReq, ok := req.(map[string]interface{})
	if !ok {
		return nil, fmt.Errorf("invalid request type")
	}

	// Формирование URL для запроса
	reqURL := fmt.Sprintf("%sv7/gate/payout/bankTransfer/swift", cj.BaseURL)

	// Выполнение запроса с помощью метода Request из пакета utils
	responseBody, err := cj.transport.Request(fasthttp.MethodPost, reqURL, assertedReq, cj.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("ошибка выполнения запроса: %w", err)
	}

	// Разбор ответа
	var res providers.PayOutResponder
	if err := json.Unmarshal(responseBody, &res); err != nil {
		return nil, fmt.Errorf("ошибка разбора ответа: %w", err)
	}

	return res, nil
}

func (cj *ClearJunction) Status(req providers.StatusRequester) (providers.StatusResponder, error) {
	assertedReq, ok := req.(StatusRequest)
	if !ok {
		return nil, fmt.Errorf("invalid request type")
	}

	reqURL := fmt.Sprintf("%sv7/gate/allocate/v2/list/iban/%s", cj.BaseURL, assertedReq.ClientCustomerId)

	// Выполнение GET запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cj.transport.Request(fasthttp.MethodGet, reqURL, nil, cj.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("failed to send Status request: %w", err)
	}

	// Преобразование тела ответа в структуру StatusResponse
	var res StatusResponse
	if err := json.Unmarshal(responseBody, &res); err != nil {
		return nil, fmt.Errorf("failed to unmarshal Status response: %w", err)
	}

	return res, nil
}

func (cj *ClearJunction) PostBack(request providers.PostBackRequester) (providers.PostBackResponder, error) {
	switch req := request.(type) {
	case *IbanPostbackRequest:
		return cj.handleIbanPostback(req)
	case *PostbackRequest:
		return cj.handlePayPostback(req)
	default:
		return nil, fmt.Errorf("unsupported postback request type")
	}
}

func (cj *ClearJunction) Custom(request providers.CustomRequester) (providers.CustomResponder, error) {
	switch req := request.(type) {
	default:
		return nil, fmt.Errorf("unsupported custom request type: %v", req)
	}
}

func (cj *ClearJunction) authMiddleware(requestBody []byte) (err error) {
	_, err = cj.Auth(AuthRequest{Body: requestBody})
	return
}

func (cj *ClearJunction) handleIbanPostback(request *IbanPostbackRequest) (providers.PostBackResponder, error) {
	switch request.Status {
	case StatusAllocated:
		err := cj.Services.DB.Pg.SetAccountIBANAndStateToActiveByOrderReference(request.OrderReference, request.Iban)
		if err != nil {
			return nil, fmt.Errorf("failed to update account: %w", err)
		}

		return &IbanPostbackResponse{OrderReference: request.OrderReference}, nil
	case StatusDeclined:
		err := cj.Services.DB.Pg.SetAccountStateToRejectedByOrderReference(request.OrderReference)
		if err != nil {
			return nil, fmt.Errorf("failed to update account: %w", err)
		}

		return &IbanPostbackResponse{OrderReference: request.OrderReference}, nil
	default:
		return nil, fmt.Errorf("wrong IBAN postback status: %s. Messages: %v", request.Status, request.Messages)
	}
}

func (cj *ClearJunction) handlePayPostback(request *PostbackRequest) (providers.PostBackResponder, error) {
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
			result, err := cj.payoutApprove(&PayoutApproveRequest{OrderReferenceArray: []string{request.OrderReference}})
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

func (cj *ClearJunction) payoutApprove(request *PayoutApproveRequest) (res PayoutApproveResponse, err error) {
	reqURL := fmt.Sprintf("%s/v7/gate/transactionAction/approve", cj.BaseURL)

	responseBody, err := cj.transport.Request("POST", reqURL, request, cj.authMiddleware)
	if err != nil {
		return res, err
	}

	resWrapped := &PayoutApproveResponseWrapped{}
	if err := json.Unmarshal(responseBody, resWrapped); err != nil {
		return res, err
	}

	if len(resWrapped.ActionResult) > 0 {
		res = resWrapped.ActionResult[0]
	} else {
		err = fmt.Errorf("empty action result")
	}

	return res, err
}

func GetName() string {
	_, fullPath, _, _ := runtime.Caller(0)
	return filepath.Base(filepath.Dir(fullPath))
}
