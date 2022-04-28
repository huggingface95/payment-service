package models

import (
	"time"
)

type PayInInvoiceRequest struct {
	PayInPayoutRequest
	ProductName string `json:"productName"`
	SiteAddress string `json:"siteAddress"`
	Label       string `json:"label"`
	SuccessUrl  string `json:"successUrl"`
	FailUrl     string `json:"failUrl"`
	PostbackUrl string `json:"postbackUrl"`
}

type PayInInvoiceResponse struct {
	PayInPayoutResponse
	Status       string                       `json:"status"`
	RedirectURL  string                       `json:"redirectURL"`
	CustomFormat PayInPayoutRequestCustomInfo `json:"customFormat"`
}

type PayInPostBack struct {
	PayInPayoutRequest
	OrderReference    string    `json:"orderReference"`
	OperTimestamp     time.Time `json:"operTimestamp"`
	Currency          string    `json:"currency"`
	Amount            float64   `json:"amount"`
	OperationCurrency string    `json:"operationCurrency"`
	OperationAmount   float64   `json:"operationAmount"`
	ProductName       string    `json:"productName"`
	SiteAddress       string    `json:"siteAddress"`
	Label             string    `json:"label"`
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
		Address struct {
			AddressOneString string `json:"addressOneString"`
		} `json:"address"`
	} `json:"payer"`
	Payee struct {
		WalletUuid       string `json:"walletUuid"`
		ClientCustomerId string `json:"clientCustomerId"`
	} `json:"payee"`
	PaymentDetails struct {
	} `json:"paymentDetails"`
	MessageUuid string                        `json:"messageUuid"`
	Type        string                        `json:"type"`
	Messages    []PayInPayoutResponseMessages `json:"messages"`
}

func NewPayInInvoiceRequest(payInPayout PayInPayoutRequest, baseUrl string) PayInInvoiceRequest {

	return PayInInvoiceRequest{
		PayInPayoutRequest: payInPayout,
		ProductName:        "Product1",
		SuccessUrl:         baseUrl + "/payin/postback",
		FailUrl:            baseUrl + "/payin/postback",
		PostbackUrl:        baseUrl + "/payin/postback",
	}
}
