package db

import (
	"github.com/jackc/pgtype"
	"time"
)

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
	ID                     int           `json:"id"`
	Amount                 *float64      `json:"amount"`
	AmountReal             *float64      `json:"amount_real"`
	Fee                    *float64      `json:"fee"`
	FeeTypeID              *int          `json:"fee_type_id"`
	CurrencyID             *int          `json:"currency_id"`
	StatusID               *int          `json:"status_id"`
	SenderName             *string       `json:"sender_name"`
	PaymentDetails         *string       `json:"payment_details"`
	SenderBankAccount      *string       `json:"sender_bank_account"`
	SenderSwift            *string       `json:"sender_swift"`
	SenderBankName         *string       `json:"sender_bank_name"`
	SenderBankCountry      *int64        `json:"sender_bank_country"`
	SenderBankAddress      *string       `json:"sender_bank_address"`
	SenderCountryID        *int64        `json:"sender_country_id"`
	SenderAddress          *string       `json:"sender_address"`
	UrgencyID              *int64        `json:"urgency_id"`
	TypeID                 *int64        `json:"type_id"`
	PaymentProviderID      *int64        `json:"payment_provider_id"`
	AccountID              *int64        `json:"account_id"`
	CompanyID              *int64        `json:"company_id"`
	MemberID               *int64        `json:"member_id"`
	PaymentNumber          *string       `json:"payment_number"`
	OperationTypeID        *int64        `json:"operation_type_id"`
	Error                  *string       `json:"error"`
	SenderAdditionalFields *pgtype.JSONB `json:"sender_additional_fields"`
	ReceivedAt             *time.Time    `json:"received_at"`
	CreatedAt              time.Time     `json:"created_at"`
	UpdatedAt              time.Time     `json:"updated_at"`

	Account       *Account              `json:"account"`
	Status        *PaymentStatus        `json:"status"`
	Provider      *Provider             `json:"provider"`
	OperationType *PaymentOperationType `json:"operationType"`
	Transaction   *Transaction          `json:"transaction"`
	Currency      *Currency             `json:"currency"`
}

// Provider представляет провайдера платежей.
type Provider struct {
	ID     int
	Name   string
	APIKey string
	APIURL string
}

// Transaction представляет транзакцию в системе.
type Transaction struct {
	ID               int64             `db:"id"`
	CompanyID        int               `db:"company_id"`
	CurrencySrcID    int               `db:"currency_src_id"`
	CurrencyDstID    int               `db:"currency_dst_id"`
	AccountSrcID     *int              `db:"account_src_id"`
	AccountDstID     *int              `db:"account_dst_id"`
	BalancePrev      float64           `db:"balance_prev"`
	BalanceNext      float64           `db:"balance_next"`
	Amount           float64           `db:"amount"`
	TxType           string            `db:"txtype"`
	CreatedAt        time.Time         `db:"created_at"`
	UpdatedAt        time.Time         `db:"updated_at"`
	TransferID       *int              `db:"transfer_id"`
	TransferType     *TransferTypeEnum `db:"transfer_type"`
	RevenueAccountID *int              `db:"revenue_account_id"`
}

// TransferIncoming представляет входящий перевод в системе
type TransferIncoming struct {
	ID                  int64     `db:"id"`
	Amount              float64   `db:"amount"`
	AmountDebt          float64   `db:"amount_debt"`
	CurrencyID          int       `db:"currency_id"`
	StatusID            int       `db:"status_id"`
	UrgencyID           int       `db:"urgency_id"`
	OperationTypeID     int       `db:"operation_type_id"`
	PaymentProviderID   int       `db:"payment_provider_id"`
	PaymentSystemID     int       `db:"payment_system_id"`
	PaymentBankID       int       `db:"payment_bank_id"`
	PaymentNumber       string    `db:"payment_number"`
	AccountID           int       `db:"account_id"`
	RecipientID         int       `db:"recipient_id"`
	RecipientType       string    `db:"recipient_type"`
	CompanyID           int       `db:"company_id"`
	SystemMessage       string    `db:"system_message"`
	Reason              string    `db:"reason"`
	Channel             string    `db:"channel"`
	BankMessage         string    `db:"bank_message"`
	SenderAccount       string    `db:"sender_account"`
	SenderBankName      string    `db:"sender_bank_name"`
	SenderBankAddress   string    `db:"sender_bank_address"`
	SenderBankSwift     string    `db:"sender_bank_swift"`
	SenderBankCountryID int       `db:"sender_bank_country_id"`
	SenderName          string    `db:"sender_name"`
	SenderCountryID     int       `db:"sender_country_id"`
	SenderCity          string    `db:"sender_city"`
	SenderAddress       string    `db:"sender_address"`
	SenderState         string    `db:"sender_state"`
	SenderZip           string    `db:"sender_zip"`
	RespondentFeesID    int       `db:"respondent_fees_id"`
	ExecutionAt         time.Time `db:"execution_at"`
	CreatedAt           time.Time `db:"created_at"`
	UpdatedAt           time.Time `db:"updated_at"`
	GroupID             int       `db:"group_id"`
	GroupTypeID         int       `db:"group_type_id"`
	ProjectID           int       `db:"project_id"`
	PriceListID         int       `db:"price_list_id"`
	PriceListFeeID      int       `db:"price_list_fee_id"`
	BeneficiaryTypeID   int       `db:"beneficiary_type_id"`
	BeneficiaryName     string    `db:"beneficiary_name"`
}
