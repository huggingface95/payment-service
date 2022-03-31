package models

import "time"

type PayoutExecutionRequest struct {
	Payment
	BankType    string  `json:"bank_type"`
	PaymentId   int64   `json:"payment_id"`
	Currency    string  `json:"currency"`
	Amount      float64 `json:"amount"`
	Description string  `json:"description"`
	ProductName string  `json:"productName"`
	SiteAddress string  `json:"siteAddress"`
	Label       string  `json:"label"`
	PostbackUrl string  `json:"postbackUrl"`
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
		} `json:"individual"`
	} `json:"payee"`
	PayeeRequisite struct {
		BankAccountNumber       string `json:"bankAccountNumber"`
		BankName                string `json:"bankName"`
		BankSwiftCode           string `json:"bankSwiftCode"`
		IntermediaryInstitution struct {
			BankCode string `json:"bankCode"`
			BankName string `json:"bankName"`
		} `json:"intermediaryInstitution"`
	} `json:"payeeRequisite"`
	PayerRequisite struct {
		BankAccountNumber       string `json:"bankAccountNumber"`
		BankName                string `json:"bankName"`
		BankSwiftCode           string `json:"bankSwiftCode"`
		IntermediaryInstitution struct {
			BankCode string `json:"bankCode"`
			BankName string `json:"bankName"`
		} `json:"intermediaryInstitution"`
	} `json:"payerRequisite"`
}

type PayoutExecutionResponse struct {
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
	CheckOnly bool `json:"checkOnly"`
}
