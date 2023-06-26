package clearjunction

import (
	"payment-service/providers"
	"payment-service/utils"
	"time"
)

type PaymentProvider interface {
	providers.PaymentProvider
}

type ClearJunction struct {
	APIKey      string
	Password    string
	BaseURL     string
	PublicURL   string
	RequestRate time.Duration
	IBANTimeout time.Duration

	Services  Services
	transport utils.FastHTTP
}

// AuthRequest представляет запрос на авторизацию.
type AuthRequest struct {
	Body []byte `json:"body"`
}

// StatusRequest представляет запрос на получение статуса.
type StatusRequest struct {
	OrderReference string `json:"order_reference"`
}

// StatusResponse представляет ответ на запрос статуса.
type StatusResponse struct {
	ClientOrder      string     `json:"clientOrder"`
	OrderReference   string     `json:"orderReference"`
	Status           StatusEnum `json:"status"`
	Messages         []Message  `json:"messages"`
	RequestReference string     `json:"requestReference"`
	Iban             string     `json:"iban,omitempty"`
}

// IbanPostbackRequest представляет модель данных для IBAN postback.
type IbanPostbackRequest struct {
	PostbackRequest
	Iban string `json:"iban"`
}

// IbanPostbackResponse представляет ответ на запрос IBAN postback.
type IbanPostbackResponse struct {
	OrderReference string `json:"orderReference"`
}

// IBANResponse представляет ответ на запрос о выделении IBAN.
type IBANResponse struct {
	RequestReference string            `json:"requestReference"`
	ClientOrder      string            `json:"clientOrder"`
	OrderReference   string            `json:"orderReference"`
	Status           StatusEnum        `json:"status"`
	ResponseMessages []ResponseMessage `json:"responseMessages"`
	IBANs            []string          `json:"ibans"`
}

// PostbackRequest представляет общую модель данных для PayIn и PayOut postback.
type PostbackRequest struct {
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
	Status            StatusEnum             `json:"status"`
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

// PayoutApproveRequest представляет запрос на утверждение PayOut.
type PayoutApproveRequest struct {
	OrderReferenceArray []string `json:"orderReferenceArray"`
}

// PayoutApproveResponse представляет ответ на утверждение PayOut.
type PayoutApproveResponse struct {
	OrderReference         string    `json:"orderReference"`
	ActionProcessingStatus string    `json:"actionProcessingStatus"`
	Messages               []Message `json:"messages"`
}

// PayoutApproveResponseWrapped представляет обёртку на ответ на утверждение PayOut.
type PayoutApproveResponseWrapped struct {
	RequestReference string                  `json:"requestReference"`
	ActionResult     []PayoutApproveResponse `json:"actionResult"`
}

type IBANRequest struct {
	ClientOrder string      `json:"clientOrder"`
	PostbackURL *string     `json:"postbackUrl,omitempty"`
	WalletUUID  *string     `json:"walletUuid,omitempty"`
	IbansGroup  *string     `json:"ibansGroup,omitempty"`
	IbanCountry *string     `json:"ibanCountry,omitempty"`
	Registrant  Registrant  `json:"registrant"`
	CustomInfo  *CustomInfo `json:"customInfo,omitempty"`
}

type PayInRequest struct {
	PostbackURL string            `json:"postbackUrl"`
	ClientOrder string            `json:"clientOrder"`
	Currency    string            `json:"currency"`
	Amount      float64           `json:"amount"`
	Description string            `json:"description"`
	ProductName string            `json:"productName"`
	SiteAddress string            `json:"siteAddress"`
	Label       string            `json:"label"`
	SuccessURL  string            `json:"successUrl"`
	FailURL     string            `json:"failUrl"`
	CustomInfo  interface{}       `json:"customInfo"`
	Payer       Payer             `json:"payer"`
	Payee       IndividualRuPayee `json:"payee"`
}

type PayOutRequest struct {
	PostbackURL string          `json:"postbackUrl"`
	ClientOrder string          `json:"clientOrder"`
	Currency    string          `json:"currency"`
	Amount      float64         `json:"amount"`
	Description string          `json:"description"`
	Payee       IndividualPayee `json:"payee"`
	CustomInfo  CustomInfo      `json:"customInfo"`
}
