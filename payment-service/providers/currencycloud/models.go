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

type PostbackRequest map[string]interface{}

// PostbackResponse представляет ответ на запрос postback.
type PostbackResponse struct {
	Status string `json:"status"`
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

// RatesRequest представляет модель данных для запроса на получение rate-ов.
type RatesRequest struct {
	BuyCurrency              string `json:"buy_currency"`
	SellCurrency             string `json:"sell_currency"`
	FixedSide                string `json:"fixed_side"`
	Amount                   int    `json:"amount"`
	OnBehalfOf               string `json:"on_behalf_of,omitempty"`
	ConversionDate           string `json:"conversion_date,omitempty"`
	ConversionDatePreference string `json:"conversion_date_preference,omitempty"`
}

// RatesImportRequest представляет модель данных для запроса на импорт rate-ов.
type RatesImportRequest struct {
	CurrencyPair string `json:"currency_pair"`
}

// RatesResponse представляет ответ на запрос на получение rate-ов.
type RatesResponse struct {
	SettlementCutOffTime time.Time `json:"settlement_cut_off_time"`
	CurrencyPair         string    `json:"currency_pair"`
	ClientBuyCurrency    string    `json:"client_buy_currency"`
	ClientSellCurrency   string    `json:"client_sell_currency"`
	ClientBuyAmount      string    `json:"client_buy_amount"`
	ClientSellAmount     string    `json:"client_sell_amount"`
	FixedSide            string    `json:"fixed_side"`
	ClientRate           string    `json:"client_rate"`
	PartnerRate          any       `json:"partner_rate"`
	CoreRate             string    `json:"core_rate"`
	DepositRequired      bool      `json:"deposit_required"`
	DepositAmount        string    `json:"deposit_amount"`
	DepositCurrency      string    `json:"deposit_currency"`
	MidMarketRate        string    `json:"mid_market_rate"`
}

// RatesImportResponse представляет ответ на запрос на импорт rate-ов.
type RatesImportResponse struct {
	Rates       map[string][]string `json:"rates"`
	Unavailable []string            `json:"unavailable"`
}

// ConvertRequest представляет модель данных для запроса на конвертацию валют.
type ConvertRequest struct {
	BuyCurrency              string `json:"buy_currency"`
	SellCurrency             string `json:"sell_currency"`
	FixedSide                string `json:"fixed_side"`
	Amount                   string `json:"amount"`
	TermAgreement            bool   `json:"term_agreement"`
	ConversionDate           string `json:"conversion_date,omitempty"`
	ClientBuyAmount          string `json:"client_buy_amount,omitempty"`
	ClientSellAmount         string `json:"client_sell_amount,omitempty"`
	Reason                   string `json:"reason,omitempty"`
	UniqueRequestId          string `json:"unique_request_id,omitempty"`
	OnBehalfOf               string `json:"on_behalf_of,omitempty"`
	ConversionDatePreference string `json:"conversion_date_preference,omitempty"`
}

// ConvertResponse представляет ответ на запрос конвертации валют.
type ConvertResponse struct {
	Id                string        `json:"id"`
	SettlementDate    time.Time     `json:"settlement_date"`
	ConversionDate    time.Time     `json:"conversion_date"`
	ShortReference    string        `json:"short_reference"`
	CreatorContactId  string        `json:"creator_contact_id"`
	AccountId         string        `json:"account_id"`
	CurrencyPair      string        `json:"currency_pair"`
	Status            string        `json:"status"`
	BuyCurrency       string        `json:"buy_currency"`
	SellCurrency      string        `json:"sell_currency"`
	ClientBuyAmount   string        `json:"client_buy_amount"`
	ClientSellAmount  string        `json:"client_sell_amount"`
	FixedSide         string        `json:"fixed_side"`
	CoreRate          string        `json:"core_rate"`
	PartnerRate       string        `json:"partner_rate"`
	PartnerBuyAmount  string        `json:"partner_buy_amount"`
	PartnerSellAmount string        `json:"partner_sell_amount"`
	ClientRate        string        `json:"client_rate"`
	DepositRequired   bool          `json:"deposit_required"`
	DepositAmount     string        `json:"deposit_amount"`
	DepositCurrency   string        `json:"deposit_currency"`
	DepositStatus     string        `json:"deposit_status"`
	DepositRequiredAt string        `json:"deposit_required_at"`
	PaymentIds        []interface{} `json:"payment_ids"`
	UnallocatedFunds  string        `json:"unallocated_funds"`
	UniqueRequestId   interface{}   `json:"unique_request_id"`
	CreatedAt         time.Time     `json:"created_at"`
	UpdatedAt         time.Time     `json:"updated_at"`
	MidMarketRate     string        `json:"mid_market_rate"`
}
