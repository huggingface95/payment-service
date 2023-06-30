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
	ID               int64     `db:"id"`
	CompanyID        int       `db:"company_id"`
	CurrencySrcID    int       `db:"currency_src_id"`
	CurrencyDstID    int       `db:"currency_dst_id"`
	AccountSrcID     *int      `db:"account_src_id"`
	AccountDstID     *int      `db:"account_dst_id"`
	BalancePrev      float64   `db:"balance_prev"`
	BalanceNext      float64   `db:"balance_next"`
	Amount           float64   `db:"amount"`
	TxType           string    `db:"txtype"`
	CreatedAt        time.Time `db:"created_at"`
	UpdatedAt        time.Time `db:"updated_at"`
	TransferID       *int      `db:"transfer_id"`
	TransferType     *string   `db:"transfer_type"`
	RevenueAccountID *int      `db:"revenue_account_id"`
}

type Currency struct {
	Id   CurrencyEnum
	Code string
}
