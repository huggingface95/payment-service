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

func New(services Services, apiKey, password, baseURL, requestRate, ibanTimeout, publicURL string) *ClearJunction {
	provider := &ClearJunction{
		APIKey:    apiKey,
		Password:  password,
		BaseURL:   baseURL,
		PublicURL: publicURL,

		Services:  services,
		transport: utils.FastHTTP{Client: &fasthttp.Client{}, ReqHeaders: map[string]string{}},
	}

	var err error
	if provider.RequestRate, err = time.ParseDuration(requestRate); err != nil {
		panic(err)
	}
	if provider.IBANTimeout, err = time.ParseDuration(ibanTimeout); err != nil {
		panic(err)
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

	// Получаем OrderReference аккаунта из БД по его ID
	orderReference, err := cj.Services.DB.Pg.GetAccountOrderReferenceOrNil(payload.AccountID)
	if err != nil {
		return nil, err
	}
	payload.OrderReference = orderReference

	// Если аккаунту уже присвоен IBAN, просто пропускаем этап генерации/получения IBAN (AccountNumber)
	if payload.AccountNumber != nil && *payload.AccountNumber != "" {
		return IBANResponse{
			OrderReference: *payload.OrderReference,
			Status:         IBANStatusAllocated,
			IBANs:          []string{*payload.AccountNumber},
		}, nil
	}

	// В зависимости от типа applicant-а запускаем процесс генерации IBAN или получаем его по готовности
	switch true {
	case payload.Applicant.Individual != nil:
		res, err := cj.generatePrivateAccountIBAN(&payload)
		if err != nil {
			return nil, err
		}

		return res, nil
	case payload.Applicant.Company != nil:
		timeout := time.After(cj.IBANTimeout)
		tick := time.Tick(cj.RequestRate)

		for {
			select {
			case <-timeout:
				return nil, fmt.Errorf("IBAN status check timeout after %s", cj.IBANTimeout)
			case <-tick:
				res, err := cj.tryToGetBusinessAccountIBAN(&payload)
				if err != nil {
					return nil, err
				}

				// Если статус равен IBANStatusAllocated или IBANStatusDeclined, выходим из цикла
				if res.Status == IBANStatusAllocated || res.Status == IBANStatusDeclined {
					return &res, nil
				}
			}
		}
	default:
		return nil, fmt.Errorf("unknown account type")
	}
}

func (cj *ClearJunction) PayIn(req providers.PayInRequester) (providers.PayInResponder, error) {
	panic("PayIn not implemented for ClearJunction provider in this service")
}

func (cj *ClearJunction) PayOut(req providers.PayOutRequester) (providers.PayOutResponder, error) {
	payload, ok := req.(queue.PayOutPayload)
	if !ok {
		return nil, fmt.Errorf("invalid request type")
	}

	postbackURL := cj.PublicURL + "clearjunction/postback"
	payeeAccountID := fmt.Sprintf("%d", payload.PayeeAccountID)
	payerAccountID := fmt.Sprintf("%d", *payload.PayerAccountID)

	request := PayOutRequest{
		ClientOrder: GenerateClientOrder(),
		PostbackURL: &postbackURL,
		Currency:    payload.Currency,
		Amount:      payload.Amount,
		Description: payload.Reason,
		Payee: Client{
			ClientCustomerID: &payeeAccountID,
		},
		Payer: &Client{
			ClientCustomerID: &payerAccountID,
		},
	}

	if payload.Payer != nil {
		switch true {
		case payload.Payer.Individual != nil:
			request.Payer.Individual = &IndividualData{
				Phone:      payload.Payer.Individual.Phone,
				Email:      payload.Payer.Individual.Email,
				BirthDate:  payload.Payer.Individual.BirthAt,
				BirthPlace: payload.Payer.Individual.BirthPlace,
				Address:    AddressData(payload.Payer.Individual.Address),
				Document: DocumentData{
					Type:              DocumentTypeEnum(payload.Payer.Individual.Document.Type),
					Number:            payload.Payer.Individual.Document.Number,
					IssuedCountryCode: payload.Payer.Individual.Document.IssuedCountryCode,
					IssuedBy:          payload.Payer.Individual.Document.IssuedBy,
					IssuedDate:        payload.Payer.Individual.Document.IssuedDate,
					ExpirationDate:    payload.Payer.Individual.Document.ExpirationDate,
				},
				LastName:   payload.Payer.Individual.LastName,
				FirstName:  payload.Payer.Individual.FirstName,
				MiddleName: payload.Payer.Individual.MiddleName,
			}
			break
		case payload.Payer.Company != nil:
			request.Payer.Corporate = &CorporateDataLight{
				Email:                payload.Payer.Company.Email,
				Name:                 payload.Payer.Company.Name,
				RegistrationNumber:   &payload.Payer.Company.RegistrationNumber,
				IncorporationCountry: &payload.Payer.Company.IncorporationCountry,
				Address:              Address(payload.Payer.Company.Address),
				IncorporationDate:    &payload.Payer.Company.IncorporationDate,
			}
			break
		}
		if payload.PayerAccountID == nil {
			return nil, fmt.Errorf("PayerAccountID must be specified when Payer specified")
		}
		payerIBAN, err := cj.Services.DB.Pg.GetAccountIBAN(*payload.PayerAccountID)
		if err != nil {
			return nil, err
		}
		switch payload.Currency {
		case "EUR":
			request.PayerRequisite = &Requisites{
				IBAN: &payerIBAN,
			}
			break
		case "GBP":
			request.PayerRequisite = &Requisites{
				SortCode: payload.PayerSortCode,
				IBAN:     &payerIBAN,
			}
			break
		default:
			return nil, fmt.Errorf("unsupported currency type: %s", payload.Currency)
		}
	}
	switch true {
	case payload.Payee.Individual != nil:
		request.Payee.Individual = &IndividualData{
			Phone:      payload.Payee.Individual.Phone,
			Email:      payload.Payee.Individual.Email,
			BirthDate:  payload.Payee.Individual.BirthAt,
			BirthPlace: payload.Payee.Individual.BirthPlace,
			Address:    AddressData(payload.Payee.Individual.Address),
			Document: DocumentData{
				Type:              DocumentTypeEnum(payload.Payee.Individual.Document.Type),
				Number:            payload.Payee.Individual.Document.Number,
				IssuedCountryCode: payload.Payee.Individual.Document.IssuedCountryCode,
				IssuedBy:          payload.Payee.Individual.Document.IssuedBy,
				IssuedDate:        payload.Payee.Individual.Document.IssuedDate,
				ExpirationDate:    payload.Payee.Individual.Document.ExpirationDate,
			},
			LastName:   payload.Payee.Individual.LastName,
			FirstName:  payload.Payee.Individual.FirstName,
			MiddleName: payload.Payee.Individual.MiddleName,
		}
		break
	case payload.Payee.Company != nil:
		request.Payee.Corporate = &CorporateDataLight{
			Email:                payload.Payee.Company.Email,
			Name:                 payload.Payee.Company.Name,
			RegistrationNumber:   &payload.Payee.Company.RegistrationNumber,
			IncorporationCountry: &payload.Payee.Company.IncorporationCountry,
			Address:              Address(payload.Payee.Company.Address),
			IncorporationDate:    &payload.Payee.Company.IncorporationDate,
		}
		break
	}
	payeeIBAN, err := cj.Services.DB.Pg.GetAccountIBAN(payload.PayeeAccountID)
	if err != nil {
		return nil, err
	}
	switch payload.Currency {
	case "EUR":
		request.PayeeRequisite = Requisites{
			IBAN: &payeeIBAN,
		}
		break
	case "GBP":
		request.PayeeRequisite = Requisites{
			SortCode: payload.PayeeSortCode,
			IBAN:     &payeeIBAN,
		}
		break
	default:
		return nil, fmt.Errorf("unsupported currency type: %s", payload.Currency)
	}

	// Формирование URL для запроса
	var transferType string
	switch payload.Currency {
	case "EUR":
		transferType = "eu"
		break
	case "GBP":
		transferType = "fps"
		break
	default:
		return nil, fmt.Errorf("unsupported currency type: %s", payload.Currency)
	}
	reqURL := fmt.Sprintf("%sv7/gate/payout/bankTransfer/%s", cj.BaseURL, transferType)

	// Выполнение запроса с помощью метода Request из пакета utils
	responseBody, err := cj.transport.Request(fasthttp.MethodPost, reqURL, request, cj.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("ошибка выполнения запроса: %w", err)
	}

	// Разбор ответа
	var res PayOutResponse
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

	reqURL := fmt.Sprintf("%sv7/gate/allocate/v2/status/iban/orderReference/%s", cj.BaseURL, assertedReq.OrderReference)

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

func (cj *ClearJunction) PostBack(req providers.PostBackRequester) (providers.PostBackResponder, error) {
	switch request := req.(type) {
	case *IBANPostbackRequest:
		return cj.handleIbanPostback(request)
	case *PayInPostbackRequest:
		return cj.handlePayInPostback(request)
	case *PayOutPostbackRequest:
		return cj.handlePayOutPostback(request)
	default:
		return nil, fmt.Errorf("unsupported postback request type")
	}
}

func (cj *ClearJunction) Custom(req providers.CustomRequester) (providers.CustomResponder, error) {
	switch request := req.(type) {
	default:
		return nil, fmt.Errorf("unsupported custom request type: %v", request)
	}
}

func (cj *ClearJunction) authMiddleware(requestBody []byte) (err error) {
	_, err = cj.Auth(AuthRequest{Body: requestBody})
	return
}

func (cj *ClearJunction) handleIbanPostback(req *IBANPostbackRequest) (providers.PostBackResponder, error) {
	switch req.Status {
	case IBANStatusAllocated:
		err := cj.Services.DB.Pg.SetAccountIBANAndStateToActiveByOrderReference(req.OrderReference, *req.Iban)
		if err != nil {
			return nil, fmt.Errorf("failed to update account: %w", err)
		}

		return &IBANPostbackResponse{OrderReference: req.OrderReference}, nil
	case IBANStatusDeclined:
		err := cj.Services.DB.Pg.SetAccountStateToRejectedByOrderReference(req.OrderReference)
		if err != nil {
			return nil, fmt.Errorf("failed to update account: %w", err)
		}

		return &IBANPostbackResponse{OrderReference: req.OrderReference}, nil
	default:
		return nil, fmt.Errorf("wrong IBAN postback status: %s. Messages: %v", req.Status, req.Messages)
	}
}

func (cj *ClearJunction) handlePayInPostback(req *PayInPostbackRequest) (providers.PostBackResponder, error) {
	return cj.handlePostback(req, PayInStatusSettled.name(), db.TransferTypeIncoming, func(balance, amount float64) float64 {
		return balance + amount
	})
}

func (cj *ClearJunction) handlePayOutPostback(req *PayOutPostbackRequest) (providers.PostBackResponder, error) {
	return cj.handlePostback(req, PayOutStatusSettled.name(), db.TransferTypeOutgoing, func(balance, amount float64) float64 {
		return balance - amount
	})
}

func (cj *ClearJunction) handlePostback(
	req PostbackRequest, settledStatus string, transferType db.TransferTypeEnum, adjustAmount func(float64, float64) float64,
) (providers.PostBackResponder, error) {
	payment, err := cj.Services.DB.Pg.GetPaymentIDStatusNameAndAccountCurrentBalanceByPaymentNumber(req.GetOrderReference())
	if err != nil {
		return nil, fmt.Errorf("failed to get payment: %w", err)
	}

	if req.GetStatus().name() == payment.Status.Name {
		return nil, fmt.Errorf("status already updated")
	}

	err = cj.Services.DB.Pg.SetPaymentAmountAndStatusByPaymentNumber(req.GetOrderReference(), req.GetAmount(), req.GetStatus().name())
	if err != nil {
		return nil, fmt.Errorf("failed to update payment: %w", err)
	}

	if req.GetStatus().name() == settledStatus {
		result, err := cj.transactionApprove(&TransactionApproveRequest{OrderReferenceArray: []string{req.GetOrderReference()}})
		if err != nil {
			return nil, err
		}

		if len(result.Messages) > 0 {
			err = fmt.Errorf("postback with any message was ignored by this handler; messages: %v", result.Messages)
			return nil, err
		}

		nextBalance := adjustAmount(payment.Account.CurrentBalance, req.GetAmount())

		if _, err = cj.Services.DB.Pg.InsertTransaction(db.Transaction{
			TransferID:   &payment.ID,
			Amount:       req.GetAmount(),
			BalancePrev:  payment.Account.CurrentBalance,
			BalanceNext:  nextBalance,
			TransferType: &transferType,
		}); err != nil {
			return nil, err
		}

		if err := cj.Services.DB.Pg.SetAccountCurrentBalance(payment.Account.ID, nextBalance); err != nil {
			return nil, err
		}
	}

	payload, err := json.Marshal(queue.EmailPayload{
		ID:      payment.ID,
		Status:  req.GetStatus().name(),
		Message: fmt.Sprintf("Payment %s postback status", req.GetType()),
		Data:    req,
	})
	if err != nil {
		return nil, err
	}

	if err := queue.PublishMessage(cj.Services.Queue, &queue.Task{
		Type:     "Email",
		Payload:  payload,
		Provider: "clearjunction",
	}); err != nil {
		return nil, err
	}

	return &PostbackResponse{OrderReference: req.GetOrderReference()}, nil
}

func (cj *ClearJunction) transactionApprove(req *TransactionApproveRequest) (res TransactionApproveResponse, err error) {
	reqURL := fmt.Sprintf("%s/v7/gate/transactionAction/approve", cj.BaseURL)

	responseBody, err := cj.transport.Request("POST", reqURL, req, cj.authMiddleware)
	if err != nil {
		return res, err
	}

	resWrapped := &TransactionApproveResponseWrapped{}
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

func (cj *ClearJunction) generatePrivateAccountIBAN(req *queue.IBANPayload) (*IBANResponse, error) {
	postbackURL := cj.PublicURL + "clearjunction/postback"

	request := IBANRequest{
		ClientOrder: GenerateClientOrder(),
		PostbackURL: &postbackURL,
		Registrant: Registrant{
			ClientCustomerID: fmt.Sprintf("%d", req.AccountID),
		},
	}

	applicant := req.Applicant.Individual
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

	reqURL := fmt.Sprintf("%sv7/gate/allocate/v2/create/iban", cj.BaseURL)

	// Выполнение POST запроса с помощью HTTP клиента из API сервиса
	responseBody, err := cj.transport.Request(fasthttp.MethodPost, reqURL, request, cj.authMiddleware)
	if err != nil {
		return nil, fmt.Errorf("failed to send IBAN request: %w", err)
	}

	var res IBANResponse
	// Преобразование тела ответа в структуру IBANResponse
	if err = json.Unmarshal(responseBody, &res); err != nil {
		return nil, fmt.Errorf("failed to unmarshal IBAN response: %w", err)
	}

	if res.Status != IBANStatusAccepted {
		return nil, fmt.Errorf("wrong status for IBAN response: %s", res.Status)
	}

	err = cj.Services.DB.Pg.SetAccountOrderReferenceAndStateToWaitingForApproval(req.AccountID, res.OrderReference)
	if err != nil {
		return nil, err
	}

	return &res, nil
}

func (cj *ClearJunction) tryToGetBusinessAccountIBAN(req *queue.IBANPayload) (IBANResponse, error) {
	var res IBANResponse

	if req.OrderReference == nil {
		return res, fmt.Errorf("order_reference field required to check IBAN allocation status")
	}

	err := cj.Services.DB.Pg.SetAccountOrderReferenceAndStateToWaitingForApproval(req.AccountID, *req.OrderReference)
	if err != nil {
		return res, err
	}

	statusRes, err := cj.Status(providers.StatusRequester(StatusRequest{
		OrderReference: *req.OrderReference,
	}))
	if err != nil {
		return res, err
	}

	statusResponse := statusRes.(StatusResponse)
	switch statusResponse.Status {
	case IBANStatusAllocated:
		res.Status = IBANStatusAllocated
		res.OrderReference = statusResponse.OrderReference
		res.IBANs = []string{*statusResponse.Iban}

		err = cj.Services.DB.Pg.SetAccountIBANAndStateToActiveByOrderReference(statusResponse.OrderReference, *statusResponse.Iban)
		if err != nil {
			return res, fmt.Errorf("failed to update account: %w", err)
		}
	case IBANStatusDeclined:
		res.Status = IBANStatusDeclined
		res.OrderReference = statusResponse.OrderReference

		err = cj.Services.DB.Pg.SetAccountStateToRejectedByOrderReference(statusResponse.OrderReference)
		if err != nil {
			return res, fmt.Errorf("failed to update account: %w", err)
		}
	default:
		return res, fmt.Errorf("unexpected account status: %s", statusResponse.Status)
	}

	return res, nil
}

func GetName() string {
	_, fullPath, _, _ := runtime.Caller(0)
	return filepath.Base(filepath.Dir(fullPath))
}
