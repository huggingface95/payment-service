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
	ID                   int
	CurrencyID           int
	OwnerID              int
	AccountNumber        string
	AccountType          string
	PaymentProviderID    int
	CommissionTemplateID int
	AccountStateID       AccountStateEnum
	AccountName          string
	IsPrimary            bool
	CurrentBalance       float64
	ReservedBalance      float64
	AvailableBalance     float64
	CreatedAt            time.Time
	UpdatedAt            time.Time
	ActivatedAt          time.Time
	OrderReference       string
	CompanyID            int
	MemberID             int
	GroupTypeID          int
	GroupRoleID          int
	PaymentSystemID      int
	PaymentBankID        int
	IsShow               bool
	EntityID             int
	MinLimitBalance      float64
	MaxLimitBalance      float64
	LastChargeAt         time.Time
	IBANProviderID       int
	ProjectID            int
	ParentID             int
	ClientType           string
	ClientID             int
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
