package clearjunction

import (
	"net/http"
	"time"
)

type ClearJunction struct {
	APIKey     string
	Password   string
	BaseURL    string
	httpClient *http.Client
	headers    map[string]string
}

// StatusRequest represents a request to Status.
type StatusRequest struct {
	ClientCustomerId string `json:"clientCustomerId"`
}

// StatusResponse represents a response of Status.
type StatusResponse struct {
	RequestReference string   `json:"requestReference"`
	ClientCustomerId string   `json:"clientCustomerId"`
	Ibans            []string `json:"ibans"`
}

// PostBackRequest represents a request to PostBack.
type PostBackRequest struct {
	ClientOrder       string              `json:"clientOrder"`
	OrderReference    string              `json:"orderReference"`
	OperTimestamp     time.Time           `json:"operTimestamp"`
	Messages          []ResponseMessage   `json:"messages"`
	Currency          string              `json:"currency"`
	Amount            float64             `json:"amount"`
	OperationCurrency string              `json:"operationCurrency"`
	OperationAmount   float64             `json:"operationAmount"`
	ProductName       string              `json:"productName"`
	SiteAddress       string              `json:"siteAddress"`
	Label             string              `json:"label"`
	CustomInfo        RequestCustomInfo   `json:"customInfo"`
	CustomFormat      RequestCustomInfo   `json:"customFormat"`
	ValuedAt          interface{}         `json:"valuedAt"`
	Status            string              `json:"status"`
	TransactionType   string              `json:"transactionType"`
	SubStatuses       PostbackSubStatuses `json:"subStatuses"`
	PaymentDetails    struct{}            `json:"paymentDetails"`
	MessageUuid       string              `json:"messageUuid"`
	Type              string              `json:"type"`
	Payer             PostbackPayeePayer  `json:"payer"`
	Payee             PostbackPayeePayer  `json:"payee"`
}

// PostBackResponse represents a response of PostBack.
type PostBackResponse struct{}

func (r StatusResponse) GetIBANs() []string {
	return r.Ibans
}
