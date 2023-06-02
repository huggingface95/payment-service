package queue

import "encoding/json"

type Task struct {
	Type     string          `json:"type"`
	Payload  json.RawMessage `json:"payload"`
	Provider string          `json:"provider"`
}

type IBANPayload struct {
	ClientOrder string     `json:"clientOrder"`
	PostbackURL string     `json:"postbackUrl"`
	WalletUUID  string     `json:"walletUuid"`
	IbansGroup  string     `json:"ibansGroup"`
	IbanCountry string     `json:"ibanCountry"`
	Registrant  Registrant `json:"registrant"`
	CustomInfo  CustomInfo `json:"customInfo"`
}

type PayInPayload struct {
	ClientOrder string            `json:"clientOrder"`
	Currency    string            `json:"currency"`
	Amount      float64           `json:"amount"`
	Description string            `json:"description"`
	ProductName string            `json:"productName"`
	SiteAddress string            `json:"siteAddress"`
	Label       string            `json:"label"`
	PostbackURL string            `json:"postbackUrl"`
	SuccessURL  string            `json:"successUrl"`
	FailURL     string            `json:"failUrl"`
	CustomInfo  interface{}       `json:"customInfo"`
	Payer       Payer             `json:"payer"`
	Payee       IndividualRuPayee `json:"payee"`
}

type PayOutPayload struct {
	ClientOrder string          `json:"clientOrder"`
	Currency    string          `json:"currency"`
	Amount      float64         `json:"amount"`
	Description string          `json:"description"`
	Payee       IndividualPayee `json:"payee"`
	CustomInfo  CustomInfo      `json:"customInfo"`
}

type EmailPayload struct {
	ID      int64       `json:"id"`
	Status  string      `json:"status"`
	Message string      `json:"message"`
	Data    interface{} `json:"data"`
}
