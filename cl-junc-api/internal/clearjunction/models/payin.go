package models

import (
	"time"
)

const (
	Captured = "captured"
)

type PayInInvoiceRequest struct {
	Payment
	PaymentType string  `json:"payment_type"`
	PaymentId   int64   `json:"payment_id"`
	ClientOrder string  `json:"clientOrder"`
	Currency    string  `json:"currency"`
	Amount      float64 `json:"amount"`
	Description string  `json:"description"`
	ProductName string  `json:"productName"`
	SiteAddress string  `json:"siteAddress"`
	Label       string  `json:"label"`
	PostbackUrl string  `json:"postbackUrl"`
	SuccessUrl  string  `json:"successUrl"`
	FailUrl     string  `json:"failUrl"`
	CustomInfo  struct {
		MyExampleParam1  string `json:"MyExampleParam1"`
		MyExampleObject1 struct {
			MyExampleParam2 string `json:"MyExampleParam2"`
			MyExampleParam3 string `json:"MyExampleParam3"`
		} `json:"MyExampleObject1"`
	} `json:"customInfo"`
	Payer struct {
		ClientCustomerId string `json:"clientCustomerId"`
		WalletUuid       string `json:"walletUuid"`
		Individual       struct {
			Phone      string `json:"phone"`
			Email      string `json:"email"`
			BirthDate  string `json:"birthDate"`
			BirthPlace string `json:"birthPlace"`
			Address    struct {
				Country string `json:"country"`
				Zip     string `json:"zip"`
				City    string `json:"city"`
				Street  string `json:"street"`
			} `json:"address"`
			Document struct {
				Type              string `json:"type"`
				Number            string `json:"number"`
				IssuedCountryCode string `json:"issuedCountryCode"`
				IssuedBy          string `json:"issuedBy"`
				IssuedDate        string `json:"issuedDate"`
				ExpirationDate    string `json:"expirationDate"`
			} `json:"document"`
			LastName   string `json:"lastName"`
			FirstName  string `json:"firstName"`
			MiddleName string `json:"middleName"`
			Inn        string `json:"inn"`
		} `json:"individual"`
	} `json:"payer"`
	Payee struct {
		ClientCustomerId string `json:"clientCustomerId"`
		WalletUuid       string `json:"walletUuid"`
		Individual       struct {
			Phone      string `json:"phone"`
			Email      string `json:"email"`
			BirthDate  string `json:"birthDate"`
			BirthPlace string `json:"birthPlace"`
			Address    struct {
				Country string `json:"country"`
				Zip     string `json:"zip"`
				City    string `json:"city"`
				Street  string `json:"street"`
			} `json:"address"`
			Document struct {
				Type              string `json:"type"`
				Number            string `json:"number"`
				IssuedCountryCode string `json:"issuedCountryCode"`
				IssuedBy          string `json:"issuedBy"`
				IssuedDate        string `json:"issuedDate"`
				ExpirationDate    string `json:"expirationDate"`
			} `json:"document"`
			LastName   string `json:"lastName"`
			FirstName  string `json:"firstName"`
			MiddleName string `json:"middleName"`
			Inn        string `json:"inn"`
		} `json:"individual"`
	} `json:"payee"`
}

type PayInInvoiceResponse struct {
	Payment
	RequestReference string    `json:"requestReference"`
	OrderReference   string    `json:"orderReference"`
	CreatedAt        time.Time `json:"createdAt"`
	Messages         []struct {
		Code    string `json:"code"`
		Message string `json:"message"`
		Details string `json:"details"`
	} `json:"messages"`
	CustomFormat struct {
		ClientCustomAttributeExample string `json:"clientCustomAttributeExample"`
	} `json:"customFormat"`
	Status      string `json:"status"`
	SubStatuses struct {
		OperStatus       string `json:"operStatus"`
		ComplianceStatus string `json:"complianceStatus"`
	} `json:"subStatuses"`
	RedirectURL string `json:"redirectURL"`
}

type PayInPostBack struct {
	Payment
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
	MessageUuid string `json:"messageUuid"`
	Type        string `json:"type"`
}
