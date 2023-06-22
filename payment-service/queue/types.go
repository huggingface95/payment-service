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
	ID      int64       `json:"id"`
	Status  string      `json:"status"`
	Message string      `json:"message"`
	Data    interface{} `json:"data"`
}

type IBANPayload struct {
	AccountID           int                  `json:"account_id"`
	AccountType         AccountTypeEnum      `json:"account_type"`
	AccountNumber       *string              `json:"account_number,omitempty"`
	ApplicantIndividual *ApplicantIndividual `json:"applicant_individual,omitempty" validate:"required_without=ApplicantCompany"`
	ApplicantCompany    *ApplicantCompany    `json:"applicant_company,omitempty" validate:"required_without=ApplicantIndividual"`
}
