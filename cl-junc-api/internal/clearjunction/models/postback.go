package models

import "time"

type PayInPayoutPostback struct {
	ClientOrder       string                         `json:"clientOrder"`
	OrderReference    string                         `json:"orderReference"`
	OperTimestamp     time.Time                      `json:"operTimestamp"`
	Messages          []PayInPayoutResponseMessages  `json:"messages"`
	Currency          string                         `json:"currency"`
	Amount            float64                        `json:"amount"`
	OperationCurrency string                         `json:"operationCurrency"`
	OperationAmount   float64                        `json:"operationAmount"`
	ProductName       string                         `json:"productName"`
	SiteAddress       string                         `json:"siteAddress"`
	Label             string                         `json:"label"`
	CustomInfo        PayInPayoutRequestCustomInfo   `json:"customInfo"`
	CustomFormat      PayInPayoutRequestCustomInfo   `json:"customFormat"`
	ValuedAt          interface{}                    `json:"valuedAt"`
	Status            string                         `json:"status"`
	TransactionType   string                         `json:"transactionType"`
	SubStatuses       PayInPayoutPostbackSubStatuses `json:"subStatuses"`
	PaymentDetails    struct{}                       `json:"paymentDetails"`
	MessageUuid       string                         `json:"messageUuid"`
	Type              string                         `json:"type"`
	Payer             PayInPayoutPostbackPayeePayer  `json:"payer"`
	Payee             PayInPayoutPostbackPayeePayer  `json:"payee"`
}

type PostbackCommon interface {
}

type PayInPayoutPostbackSubStatuses struct {
	OperStatus       string `json:"operStatus"`
	ComplianceStatus string `json:"complianceStatus"`
}

type PayInPayoutPostbackPayeePayer struct {
	WalletUuid       string `json:"walletUuid"`
	ClientCustomerId string `json:"clientCustomerId"`
}
