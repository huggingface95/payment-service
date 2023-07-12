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
	ClientOrder      string         `json:"clientOrder"`
	OrderReference   string         `json:"orderReference"`
	Status           IBANStatusEnum `json:"status"`
	Messages         []Message      `json:"messages"`
	RequestReference string         `json:"requestReference"`
	Iban             *string        `json:"iban,omitempty"`
}

// IBANRequest представляет модель данных для запроса IBAN.
type IBANRequest struct {
	ClientOrder string      `json:"clientOrder"`
	PostbackURL *string     `json:"postbackUrl,omitempty"`
	WalletUUID  *string     `json:"walletUuid,omitempty"`
	IbansGroup  *string     `json:"ibansGroup,omitempty"`
	IbanCountry *string     `json:"ibanCountry,omitempty"`
	Registrant  Registrant  `json:"registrant"`
	CustomInfo  *CustomInfo `json:"customInfo,omitempty"`
}

// IBANPostbackRequest представляет модель данных для IBAN postback.
type IBANPostbackRequest struct {
	ClientOrder    string         `json:"clientOrder"`
	OrderReference string         `json:"orderReference"`
	Status         IBANStatusEnum `json:"status"`
	Messages       []Message      `json:"messages"`
	MessageUuid    string         `json:"messageUuid"`
	Iban           *string        `json:"iban,omitempty"`
	Type           string         `json:"type"`
}

// IBANPostbackResponse представляет ответ на запрос IBAN postback.
type IBANPostbackResponse struct {
	OrderReference string `json:"orderReference"`
}

// IBANResponse представляет ответ на запрос о выделении IBAN.
type IBANResponse struct {
	RequestReference string            `json:"requestReference"`
	ClientOrder      string            `json:"clientOrder"`
	OrderReference   string            `json:"orderReference"`
	Status           IBANStatusEnum    `json:"status"`
	ResponseMessages []ResponseMessage `json:"responseMessages"`
	IBANs            []string          `json:"ibans"`
}

type PostbackRequest interface {
	GetOrderReference() string
	GetAmount() float64
	GetStatus() PostbackStatus
	GetType() string
}

type PostbackStatus interface {
	name() string
}

// PayInPostbackRequest представляет модель данных для PayIn postback.
type PayInPostbackRequest struct {
	ClientOrder       string              `json:"clientOrder"`
	OrderReference    string              `json:"orderReference"`
	OperTimestamp     time.Time           `json:"operTimestamp"`
	Messages          []Message           `json:"messages"`
	Currency          string              `json:"currency"`
	Amount            float64             `json:"amount"`
	OperationCurrency string              `json:"operationCurrency"`
	OperationAmount   float64             `json:"operationAmount"`
	ProductName       *string             `json:"productName,omitempty"`
	SiteAddress       *string             `json:"siteAddress,omitempty"`
	Label             *string             `json:"label,omitempty"`
	CustomInfo        *CustomInfo         `json:"customInfo,omitempty"`
	CustomFormat      *CustomFormat       `json:"customFormat,omitempty"`
	ValuedAt          time.Time           `json:"valuedAt"`
	Status            PayInStatusEnum     `json:"status"`
	MessageUuid       string              `json:"messageUuid"`
	TransactionType   string              `json:"transactionType"`
	SubStatuses       SubStatuses         `json:"subStatuses"`
	Payer             *PayInPostbackPayer `json:"payer,omitempty"`
	Payee             *PayInPostbackPayee `json:"payee,omitempty"`
	PaymentDetails    PaymentDetails      `json:"paymentDetails"`
	Type              string              `json:"type"`
}

// PostbackResponse представляет ответ на запрос PayIn и PayOut postback.
type PostbackResponse struct {
	OrderReference string `json:"orderReference"`
}

// PayOutRequest представляет модель данных для запроса PayOut.
type PayOutRequest struct {
	ClientOrder    string      `json:"clientOrder"`
	PostbackURL    *string     `json:"postbackUrl,omitempty"`
	Currency       string      `json:"currency"`
	Amount         float64     `json:"amount"`
	Description    string      `json:"description"`
	CustomInfo     *CustomInfo `json:"customInfo,omitempty"`
	Payer          *Client     `json:"payer,omitempty"`
	Payee          Client      `json:"payee"`
	PayerRequisite *Requisites `json:"payerRequisite,omitempty"`
	PayeeRequisite Requisites  `json:"payeeRequisite"`
}

// PayOutResponse представляет ответ на запрос PayOut.
type PayOutResponse struct {
	RequestReference string           `json:"requestReference"`
	ClientOrder      string           `json:"clientOrder"`
	OrderReference   string           `json:"orderReference"`
	CreatedAt        time.Time        `json:"createdAt"`
	Status           PayOutStatusEnum `json:"status"`
	Messages         []Message        `json:"messages"`
	CustomFormat     *CustomFormat    `json:"customFormat,omitempty"`
	CheckOnly        bool             `json:"checkOnly"`
	SubStatuses      SubStatuses      `json:"subStatuses"`
}

// PayOutPostbackRequest представляет модель данных для PayOut postback.
type PayOutPostbackRequest struct {
	ClientOrder       string           `json:"clientOrder"`
	OrderReference    string           `json:"orderReference"`
	OperTimestamp     string           `json:"operTimestamp"`
	Messages          []Message        `json:"messages"`
	Currency          string           `json:"currency"`
	Amount            float64          `json:"amount"`
	OperationCurrency string           `json:"operationCurrency"`
	OperationAmount   float64          `json:"operationAmount"`
	ProductName       *string          `json:"productName,omitempty"`
	SiteAddress       *string          `json:"siteAddress,omitempty"`
	Label             *string          `json:"label,omitempty"`
	CustomInfo        *CustomInfo      `json:"customInfo,omitempty"`
	CustomFormat      *CustomFormat    `json:"customFormat,omitempty"`
	ValuedAt          string           `json:"valuedAt"`
	Status            PayOutStatusEnum `json:"status"`
	MessageUuid       string           `json:"messageUuid"`
	TransactionType   string           `json:"transactionType"`
	SubStatuses       SubStatuses      `json:"subStatuses"`
	Payer             *Client          `json:"payer,omitempty"`
	PaymentDetails    PaymentDetails   `json:"paymentDetails"`
	Type              string           `json:"type"`
}

// TransactionApproveRequest представляет запрос на утверждение транзакции.
type TransactionApproveRequest struct {
	OrderReferenceArray []string `json:"orderReferenceArray"`
}

// TransactionApproveResponse представляет ответ на утверждение транзакции.
type TransactionApproveResponse struct {
	OrderReference         string    `json:"orderReference"`
	ActionProcessingStatus string    `json:"actionProcessingStatus"`
	Messages               []Message `json:"messages"`
}

// TransactionApproveResponseWrapped представляет обёртку на ответ на утверждение транзакции.
type TransactionApproveResponseWrapped struct {
	RequestReference string                       `json:"requestReference"`
	ActionResult     []TransactionApproveResponse `json:"actionResult"`
}

func (s PayOutStatusEnum) toString() string {
	return string(s)
}

func (s PayOutStatusEnum) name() string {
	switch s {
	case PayOutStatusCreated:
		return "Waiting execution date"
	case PayOutStatusCanceled:
		return "Canceled"
	case PayOutStatusPending:
		return "Pending"
	case PayOutStatusSettled:
		return "Sent"
	case PayOutStatusDeclined:
		return "Error"
	default:
		return "Unknown"
	}
}

func (s PayInStatusEnum) toString() string {
	return string(s)
}

func (s PayInStatusEnum) name() string {
	switch s {
	case PayInStatusCreated:
		return "Waiting execution date"
	case PayInStatusExpired, PayInStatusCanceled:
		return "Canceled"
	case PayInStatusRejected:
		return "Error"
	case PayInStatusReturned:
		return "Refund"
	case PayInStatusPending, PayInStatusCaptured:
		return "Pending"
	case PayInStatusAuthorized:
		return "Sent"
	case PayInStatusSettled:
		return "Unsigned"
	case PayInStatusDeclined:
		return "Error"
	default:
		return "Unknown"
	}
}

func (p *PayInPostbackRequest) GetOrderReference() string {
	return p.OrderReference
}

func (p *PayInPostbackRequest) GetAmount() float64 {
	return p.Amount
}

func (p *PayInPostbackRequest) GetStatus() PostbackStatus {
	return p.Status
}

func (p *PayInPostbackRequest) GetType() string {
	return "PayIn"
}

func (p *PayOutPostbackRequest) GetOrderReference() string {
	return p.OrderReference
}

func (p *PayOutPostbackRequest) GetAmount() float64 {
	return p.Amount
}

func (p *PayOutPostbackRequest) GetStatus() PostbackStatus {
	return p.Status
}

func (p *PayOutPostbackRequest) GetType() string {
	return "PayIn"
}
