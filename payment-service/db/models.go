package db

import "time"

// IBAN представляет сгенерированный IBAN.
type IBAN struct {
	ID            int
	TransactionID int
	IBANNumber    string
	IBANCountry   string
	CreatedAt     time.Time
}

// Payment представляет платеж в системе.
type Payment struct {
	ID                     int
	Amount                 float64
	AmountReal             float64
	Fee                    float64
	FeeTypeID              int
	CurrencyID             CurrencyEnum
	StatusID               StatusEnum
	SenderName             string
	PaymentDetails         string
	SenderBankAccount      string
	SenderSWIFT            string
	SenderBankName         string
	SenderBankCountry      int
	SenderBankAddress      string
	SenderCountryID        int
	SenderAddress          string
	UrgencyID              int
	TypeID                 int
	PaymentProviderID      int
	AccountID              int
	CompanyID              int
	MemberID               int
	PaymentNumber          string
	OperationTypeId        OperationTypeEnum
	Error                  string
	ReceivedAt             time.Time
	SenderAdditionalFields []byte
	CreatedAt              time.Time
	UpdatedAt              time.Time

	Account       *Account
	Status        *PaymentStatus
	Provider      *Provider
	OperationType *PaymentOperationType
	Transaction   *Transaction
	Currency      *Currency
}

// Account представляет аккаунт в системе.
type Account struct {
	ID                   int              `json:"id"`
	CurrencyID           int              `json:"currency_id"`
	OwnerID              int              `json:"owner_id"`
	AccountNumber        *string          `json:"account_number,omitempty"`
	AccountType          string           `json:"account_type"`
	PaymentProviderID    int              `json:"payment_provider_id"`
	CommissionTemplateID int              `json:"commission_template_id"`
	AccountStateID       AccountStateEnum `json:"account_state_id"`
	AccountName          *string          `json:"account_name,omitempty"`
	IsPrimary            bool             `json:"is_primary"`
	CurrentBalance       float64          `json:"current_balance"`
	ReservedBalance      float64          `json:"reserved_balance"`
	AvailableBalance     float64          `json:"available_balance"`
	CreatedAt            time.Time        `json:"created_at"`
	UpdatedAt            time.Time        `json:"updated_at"`
	ActivatedAt          *time.Time       `json:"activated_at,omitempty"`
	OrderReference       *string          `json:"order_reference,omitempty"`
	CompanyID            *int             `json:"company_id,omitempty"`
	MemberID             int              `json:"member_id"`
	GroupTypeID          int              `json:"group_type_id"`
	GroupRoleID          int              `json:"group_role_id"`
	PaymentSystemID      *int             `json:"payment_system_id,omitempty"`
	PaymentBankID        *int             `json:"payment_bank_id,omitempty"`
	IsShow               bool             `json:"is_show"`
	EntityID             *int             `json:"entity_id,omitempty"`
	MinLimitBalance      float64          `json:"min_limit_balance"`
	MaxLimitBalance      float64          `json:"max_limit_balance"`
	LastChargeAt         *time.Time       `json:"last_charge_at,omitempty"`
	IBANProviderID       *int             `json:"iban_provider_id,omitempty"`
	ProjectID            *int             `json:"project_id,omitempty"`
	ParentID             *int             `json:"parent_id,omitempty"`
	ClientType           *string          `json:"client_type,omitempty"`
	ClientID             *int             `json:"client_id,omitempty"`
	IBAN                 *string          `json:"iban,omitempty"`
}

type PaymentStatus struct {
	Id   StatusEnum
	Name string
}

// Provider представляет провайдера платежей.
type Provider struct {
	ID     int
	Name   string
	APIKey string
	APIURL string
}

type PaymentOperationType struct {
	Id   OperationTypeEnum
	Name string
}

// Transaction представляет транзакцию в системе.
type Transaction struct {
	ID              int64   `json:"id"`
	ProviderID      int64   `json:"provider_id"`
	ClientOrder     string  `json:"client_order"`
	Status          string  `json:"status"`
	TransactionType string  `json:"transaction_type"`
	Amount          float64 `json:"amount"`
	Currency        string  `json:"currency"`
}

type Currency struct {
	Id   CurrencyEnum
	Code string
}
