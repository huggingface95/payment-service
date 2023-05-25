package clearjunction

import (
	"payment-service/providers"
	"payment-service/utils"
	"time"
)

const (
	StatusAccepted  = "accepted"
	StatusPending   = "pending"
	StatusAllocated = "allocated"
	StatusDeclined  = "declined"
	StatusCreated   = "created"
)

type PaymentProvider interface {
	providers.PaymentProvider
}

type ClearJunction struct {
	APIKey    string
	Password  string
	BaseURL   string
	PublicURL string

	Services  Services
	transport utils.FastHTTP
}

// AuthRequest представляет запрос на авторизацию.
type AuthRequest struct {
	Body []byte `json:"body"`
}

// StatusRequest представляет запрос на получение статуса.
type StatusRequest struct {
	ClientCustomerId string `json:"clientCustomerId"`
}

// StatusResponse представляет ответ на запрос статуса.
type StatusResponse struct {
	RequestReference string   `json:"requestReference"`
	ClientCustomerId string   `json:"clientCustomerId"`
	Ibans            []string `json:"ibans"`
}

// IbanPostbackRequest представляет модель данных для IBAN postback.
type IbanPostbackRequest struct {
	PayPostbackRequest
	Iban string `json:"iban"`
}

// IbanPostbackResponse представляет ответ на запрос IBAN postback.
type IbanPostbackResponse struct {
	OrderReference string `json:"orderReference"`
}

// PayPostbackRequest представляет общую модель данных для PayIn и PayOut postback.
type PayPostbackRequest struct {
	ClientOrder       string                 `json:"clientOrder"`
	OrderReference    string                 `json:"orderReference"`
	OperTimestamp     time.Time              `json:"operTimestamp"`
	Messages          []PayRequestMessage    `json:"messages"`
	Currency          string                 `json:"currency"`
	Amount            float64                `json:"amount"`
	OperationCurrency string                 `json:"operationCurrency"`
	OperationAmount   float64                `json:"operationAmount"`
	ProductName       string                 `json:"productName"`
	SiteAddress       string                 `json:"siteAddress"`
	Label             string                 `json:"label"`
	CustomInfo        PayRequestCustomInfo   `json:"customInfo"`
	CustomFormat      PayRequestCustomInfo   `json:"customFormat"`
	ValuedAt          interface{}            `json:"valuedAt"`
	Status            string                 `json:"status"`
	TransactionType   string                 `json:"transactionType"`
	SubStatuses       PayPostbackSubStatuses `json:"subStatuses"`
	PaymentDetails    struct{}               `json:"paymentDetails"`
	MessageUuid       string                 `json:"messageUuid"`
	Type              string                 `json:"type"`
	Payer             PayPostbackPayeePayer  `json:"payer"`
	Payee             PayPostbackPayeePayer  `json:"payee"`
}

// PayPostbackResponse представляет ответ на запрос PayIn и PayOut postback.
type PayPostbackResponse struct {
	OrderReference string `json:"orderReference"`
}

// PayoutApproveResponse представляет ответ на утверждение PayOut.
type PayoutApproveResponse struct {
	OrderReference         string    `json:"orderReference"`
	ActionProcessingStatus string    `json:"actionProcessingStatus"`
	Messages               []Message `json:"messages"`
}

// PayoutApproveResponseWrapper представляет обёртку на ответ на утверждение PayOut.
type PayoutApproveResponseWrapper struct {
	RequestReference string                  `json:"requestReference"`
	ActionResult     []PayoutApproveResponse `json:"actionResult"`
}

// IBANResponse представляет ответ на запрос о выделении IBAN.
type IBANResponse struct {
	RequestReference string            `json:"requestReference"`
	ClientOrder      string            `json:"clientOrder"`
	OrderReference   string            `json:"orderReference"`
	Status           string            `json:"status"`
	ResponseMessages []ResponseMessage `json:"responseMessages"`
	IBANs            []string          `json:"ibans"`
}
