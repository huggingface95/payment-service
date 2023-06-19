package api

import (
	"payment-service/db"
	"payment-service/providers"
	"payment-service/queue"
)

type Services struct {
	API       *Service
	Providers *providers.Service
	Queue     *queue.Service
	DB        *db.Service
}

// InputAccount тип данных, представляющий создание или обновление учетной записи
type InputAccount struct {
	CompanyID            string   `json:"company_id"`
	CurrencyID           string   `json:"currency_id"`
	OwnerID              string   `json:"owner_id"`
	AccountNumber        *string  `json:"account_number"`
	PaymentProviderID    string   `json:"payment_provider_id"`
	IBANProviderID       *string  `json:"iban_provider_id"`
	CommissionTemplateID string   `json:"commission_template_id"`
	AccountName          string   `json:"account_name"`
	IsPrimary            bool     `json:"is_primary"`
	GroupRoleID          string   `json:"group_role_id"`
	GroupTypeID          string   `json:"group_type_id"`
	PaymentBankID        *string  `json:"payment_bank_id"`
	ProjectID            *string  `json:"project_id"`
	ParentID             string   `json:"parent_id"`
	ClientID             *string  `json:"client_id"`
	MinLimitBalance      *float64 `json:"min_limit_balance"`
	MaxLimitBalance      *float64 `json:"max_limit_balance"`
	CurrentBalance       *float64 `json:"current_balance"`
}
