package currencycloud

import (
	"payment-service/providers"
	"payment-service/utils"
	"time"
)

const (
	StatusAccepted  = "accepted"
	StatusPending   = "pending"
	StatusAllocated = "allocated"
	StatusDeclined  = "declined"
	StatusCreated   = "created"
)

type PaymentProvider interface {
	providers.PaymentProvider
}

type CurrencyCloud struct {
	APIKey    string `json:"api_key"`
	LoginID   string `json:"login_id"`
	BaseURL   string `json:"BaseURL"`
	PublicURL string `json:"PublicURL"`

	Services  Services
	transport utils.FastHTTP
}

// AuthRequest представляет запрос на авторизацию.
type AuthRequest struct {
	LoginID string `json:"login_id"`
	ApiKey  string `json:"api_key"`
}

// AuthResponse представляет ответ на запрос авторизации.
type AuthResponse struct {
	AuthToken string `json:"auth_token"`
}

// StatusRequest представляет запрос на получение статуса транзакции.
type StatusRequest struct {
	PaymentID string `json:"paymentId"`
}

// StatusResponse представляет ответ на запрос статуса.
type StatusResponse struct {
	ID     string `json:"id"`
	Status string `json:"status"`
}

// IBANRequest представляет запрос на создания beneficiary с iban или account_number.
type IBANRequest struct {
	Name                           string   `json:"name"`
	BankAccountHolderName          string   `json:"bank_account_holder_name"`
	BankCountry                    string   `json:"bank_country"`
	Currency                       string   `json:"currency"`
	Email                          string   `json:"email,omitempty"`
	BeneficiaryCountry             string   `json:"beneficiary_country,omitempty"`
	AccountNumber                  string   `json:"account_number,omitempty"`
	RoutingCodeType1               string   `json:"routing_code_type_1,omitempty"`
	RoutingCodeValue1              string   `json:"routing_code_value_1,omitempty"`
	RoutingCodeType2               string   `json:"routing_code_type_2,omitempty"`
	RoutingCodeValue2              string   `json:"routing_code_value_2,omitempty"`
	BicSwift                       string   `json:"bic_swift,omitempty"`
	IBAN                           string   `json:"iban,omitempty"`
	DefaultBeneficiary             bool     `json:"default_beneficiary,omitempty"`
	BankAddress                    string   `json:"bank_address,omitempty"`
	BankName                       string   `json:"bank_name,omitempty"`
	BankAccountType                string   `json:"bank_account_type,omitempty"`
	BeneficiaryEntityType          string   `json:"beneficiary_entity_type,omitempty"`
	BeneficiaryCompanyName         string   `json:"beneficiary_company_name,omitempty"`
	BeneficiaryFirstName           string   `json:"beneficiary_first_name,omitempty"`
	BeneficiaryLastName            string   `json:"beneficiary_last_name,omitempty"`
	BeneficiaryCity                string   `json:"beneficiary_city,omitempty"`
	BeneficiaryPostcode            string   `json:"beneficiary_postcode,omitempty"`
	BeneficiaryStateOrProvince     string   `json:"beneficiary_state_or_province,omitempty"`
	BeneficiaryAddress             []string `json:"beneficiary_address[],omitempty"`
	BeneficiaryDateOfBirth         string   `json:"beneficiary_date_of_birth,omitempty"`
	BeneficiaryIdentificationType  string   `json:"beneficiary_identification_type,omitempty"`
	BeneficiaryIdentificationValue string   `json:"beneficiary_identification_value,omitempty"`
	PaymentTypes                   []string `json:"payment_types[],omitempty"`
	OnBehalfOf                     string   `json:"on_behalf_of,omitempty"`
	BeneficiaryExternalReference   string   `json:"beneficiary_external_reference,omitempty"`
}

// IBANResponse представляет ответ на запрос создания beneficiary с iban или account_number.
type IBANResponse struct {
	ID                             string    `json:"id"`
	BankAccountHolderName          string    `json:"bank_account_holder_name"`
	Name                           string    `json:"name"`
	Email                          any       `json:"email"`
	PaymentTypes                   []string  `json:"payment_types"`
	BeneficiaryAddress             []string  `json:"beneficiary_address"`
	BeneficiaryCountry             string    `json:"beneficiary_country"`
	BeneficiaryEntityType          string    `json:"beneficiary_entity_type"`
	BeneficiaryCompanyName         any       `json:"beneficiary_company_name"`
	BeneficiaryFirstName           string    `json:"beneficiary_first_name"`
	BeneficiaryLastName            string    `json:"beneficiary_last_name"`
	BeneficiaryCity                string    `json:"beneficiary_city"`
	BeneficiaryPostcode            any       `json:"beneficiary_postcode"`
	BeneficiaryStateOrProvince     any       `json:"beneficiary_state_or_province"`
	BeneficiaryDateOfBirth         any       `json:"beneficiary_date_of_birth"`
	BeneficiaryIdentificationType  any       `json:"beneficiary_identification_type"`
	BeneficiaryIdentificationValue any       `json:"beneficiary_identification_value"`
	BankCountry                    string    `json:"bank_country"`
	BankName                       string    `json:"bank_name"`
	BankAccountType                any       `json:"bank_account_type"`
	Currency                       string    `json:"currency"`
	AccountNumber                  string    `json:"account_number"`
	RoutingCodeType1               any       `json:"routing_code_type_1"`
	RoutingCodeValue1              any       `json:"routing_code_value_1"`
	RoutingCodeType2               any       `json:"routing_code_type_2"`
	RoutingCodeValue2              any       `json:"routing_code_value_2"`
	BicSwift                       string    `json:"bic_swift"`
	IBAN                           any       `json:"iban"`
	DefaultBeneficiary             string    `json:"default_beneficiary"`
	CreatorContactID               string    `json:"creator_contact_id"`
	BankAddress                    []string  `json:"bank_address"`
	CreatedAt                      time.Time `json:"created_at"`
	UpdatedAt                      time.Time `json:"updated_at"`
	BeneficiaryExternalReference   any       `json:"beneficiary_external_reference"`
}

// PayInRequest представляет общую модель данных для запроса PayIn.
type PayInRequest struct {
}

// PayOutRequest представляет общую модель данных для запроса PayOut.
type PayOutRequest struct {
	Currency        string `json:"currency"`
	BeneficiaryID   string `json:"beneficiary_id"`
	Amount          string `json:"amount"`
	Reason          string `json:"reason"`
	Reference       string `json:"reference"`
	UniqueRequestID string `json:"unique_request_id"`
}

// AccountRequest представляет модель данных для запроса на создание Account.
type AccountRequest struct {
	AccountName     string `json:"account_name"`
	LegalEntityType string `json:"legal_entity_type"`
	Street          string `json:"street"`
	City            string `json:"city"`
	PostalCode      string `json:"postal_code"`
	Country         string `json:"country"`
}

// AccountResponse представляет ответ на запрос о создании Account.
type AccountResponse struct {
	ID                         string    `json:"id"`
	AccountName                string    `json:"account_name"`
	Brand                      string    `json:"brand"`
	YourReference              any       `json:"your_reference"`
	Status                     string    `json:"status"`
	Street                     string    `json:"street"`
	City                       string    `json:"city"`
	StateOrProvince            any       `json:"state_or_province"`
	Country                    string    `json:"country"`
	PostalCode                 string    `json:"postal_code"`
	SpreadTable                string    `json:"spread_table"`
	LegalEntityType            string    `json:"legal_entity_type"`
	CreatedAt                  time.Time `json:"created_at"`
	UpdatedAt                  time.Time `json:"updated_at"`
	IdentificationType         any       `json:"identification_type"`
	IdentificationValue        any       `json:"identification_value"`
	ShortReference             string    `json:"short_reference"`
	APITrading                 bool      `json:"api_trading"`
	OnlineTrading              bool      `json:"online_trading"`
	PhoneTrading               bool      `json:"phone_trading"`
	ProcessThirdPartyFunds     bool      `json:"process_third_party_funds"`
	SettlementType             string    `json:"settlement_type"`
	AgentOrReliance            bool      `json:"agent_or_reliance"`
	TermsAndConditionsAccepted any       `json:"terms_and_conditions_accepted"`
	BankAccountVerified        string    `json:"bank_account_verified"`
}

type PostbackRequest map[string]interface{}

// PostbackResponse представляет ответ на запрос postback.
type PostbackResponse struct {
	Status string `json:"status"`
}
