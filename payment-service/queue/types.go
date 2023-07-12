package queue

import (
	"encoding/json"
)

type Task struct {
	Type     string          `json:"type"`
	Payload  json.RawMessage `json:"payload"`
	Provider string          `json:"provider"`
}

type EmailPayload struct {
	ID      int         `json:"id"`
	Status  string      `json:"status"`
	Message string      `json:"message"`
	Data    interface{} `json:"data"`
}

type IBANPayload struct {
	AccountID      int       `json:"account_id"`
	AccountNumber  *string   `json:"account_number,omitempty"`
	OrderReference *string   `json:"order_reference,omitempty"`
	Applicant      Applicant `json:"applicant"`
}

type PayOutPayload struct {
	Currency       string     `json:"currency"`
	Amount         float64    `json:"amount"`
	Reason         string     `json:"reason"`
	Payer          *Applicant `json:"payer,omitempty"`
	PayerAccountID *int       `json:"payer_account_id,omitempty"`
	PayerSortCode  *string    `json:"payer_sort_code,omitempty"`
	Payee          Applicant  `json:"payee"`
	PayeeAccountID int        `json:"payee_account_id"`
	PayeeSortCode  *string    `json:"payee_sort_code,omitempty"`
}
