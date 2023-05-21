package db

import "time"

// Transaction представляет транзакцию в системе.
type Transaction struct {
	ID              int
	ProviderID      int
	ClientOrder     string
	Status          string
	TransactionType string
	Amount          float64
	Currency        string
	CreatedAt       time.Time
	UpdatedAt       time.Time
}

// IBAN представляет сгенерированный IBAN.
type IBAN struct {
	ID            int
	TransactionID int
	IBANNumber    string
	IBANCountry   string
	CreatedAt     time.Time
}

// Provider представляет провайдера платежей.
type Provider struct {
	ID     int
	Name   string
	APIKey string
	APIURL string
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
	AccountStateID       int
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
