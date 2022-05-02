package models

import "time"

type PayInPayoutStatusResponse struct {
	ClientOrder    string    `json:"clientOrder"`
	OrderReference string    `json:"orderReference"`
	OperTimestamp  time.Time `json:"operTimestamp"`
	Messages       []struct {
		Code    string `json:"code"`
		Message string `json:"message"`
		Details string `json:"details"`
	} `json:"messages"`
	Currency          string  `json:"currency"`
	Amount            float64 `json:"amount"`
	OperationCurrency string  `json:"operationCurrency"`
	OperationAmount   float64 `json:"operationAmount"`
	ProductName       string  `json:"productName"`
	SiteAddress       string  `json:"siteAddress"`
	Label             string  `json:"label"`
	CustomInfo        struct {
		MyExampleParam1  string `json:"MyExampleParam1"`
		MyExampleObject1 struct {
			MyExampleParam2 string `json:"MyExampleParam2"`
			MyExampleParam3 string `json:"MyExampleParam3"`
		} `json:"MyExampleObject1"`
	} `json:"customInfo"`
	CustomFormat struct {
		ClientCustomAttributeExample string `json:"clientCustomAttributeExample"`
	} `json:"customFormat"`
	ValuedAt        interface{} `json:"valuedAt"`
	Status          string      `json:"status"`
	TransactionType string      `json:"transactionType"`
	SubStatuses     struct {
		OperStatus       string `json:"operStatus"`
		ComplianceStatus string `json:"complianceStatus"`
	} `json:"subStatuses"`
	Payer struct {
		WalletUuid       string `json:"walletUuid"`
		ClientCustomerId string `json:"clientCustomerId"`
	} `json:"payer"`
	PaymentDetails   struct{} `json:"paymentDetails"`
	RequestReference string   `json:"requestReference"`
}
