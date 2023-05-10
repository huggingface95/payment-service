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
