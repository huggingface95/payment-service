package clearjunction

import (
	"payment-service/utils"
)

// ResponseMessage представляет сообщение ответа с кодом, сообщением и деталями.
type ResponseMessage struct {
	Code    string `json:"code"`
	Message string `json:"message"`
	Details string `json:"details"`
}

// SubStatuses представляет дополнительные статусы для postback.
type SubStatuses struct {
	OperStatus       string `json:"operStatus"`
	ComplianceStatus string `json:"complianceStatus"`
}

// PayInPostbackPayee представляет информацию об отправителе платежа для PayIn postback запроса.
type PayInPostbackPayee struct {
	WalletUuid       *string `json:"walletUuid,omitempty"`
	ClientCustomerId *string `json:"clientCustomerId,omitempty"`
}

// PayInPostbackPayer представляет информацию о получателе платежа для PayIn postback запроса.
type PayInPostbackPayer struct {
	Address *PayInPostbackPayerAddress `json:"address,omitempty"`
}

// PayInPostbackPayerAddress представляет информацию об адресе отправителя для PayIn postback запроса.
type PayInPostbackPayerAddress struct {
	AddressOneString *string `json:"addressOneString,omitempty"`
	Country          *string `json:"country,omitempty"`
}

// Message представляет сообщение с кодом, сообщением и деталями.
type Message struct {
	Code    string `json:"code"`
	Message string `json:"message"`
	Details string `json:"details"`
}

// Registrant представляет информацию о заявителе IBAN.
type Registrant struct {
	ClientCustomerID string          `json:"clientCustomerId"`
	Individual       *IndividualData `json:"individual,omitempty"`
	Corporate        *CorporateData  `json:"corporate,omitempty"`
}

// IndividualData представляет данные об индивидуальном клиенте.
type IndividualData struct {
	Phone      *string               `json:"phone,omitempty"`
	Email      *string               `json:"email,omitempty"`
	BirthDate  utils.ISOExtendedDate `json:"birthDate"`
	BirthPlace *string               `json:"birthPlace,omitempty"`
	Address    AddressData           `json:"address"`
	Document   DocumentData          `json:"document"`
	LastName   string                `json:"lastName"`
	FirstName  string                `json:"firstName"`
	MiddleName *string               `json:"middleName,omitempty"`
}

// CorporateData представляет модель данных о корпоративном клиенте.
type CorporateData struct {
	Email                   *string                   `json:"email,omitempty"`
	Name                    string                    `json:"name"`
	RegistrationNumber      string                    `json:"registrationNumber"`
	IncorporationCountry    string                    `json:"incorporationCountry"`
	Address                 Address                   `json:"address"`
	IncorporationDate       utils.ISOExtendedDate     `json:"incorporationDate"`
	UltimateBeneficialOwner []UltimateBeneficialOwner `json:"ultimateBeneficialOwner"`
	TradingWebsite          *string                   `json:"tradingWebsite,omitempty"`
	ExpectedTurnover        int                       `json:"expectedTurnover"`
	BeneficialLegalEntity   *string                   `json:"beneficialLegalEntity,omitempty"`
	OtherDetails            OtherDetails              `json:"otherDetails"`
	BusinessPartners        []BusinessPartner         `json:"businessPartners"`
	FundFlows               FundFlows                 `json:"fundFlows"`
	ComplianceEvaluation    ComplianceEvaluation      `json:"complianceEvaluation"`
	CustomOptions           *CustomInfo               `json:"customOptions,omitempty"`
}

// CorporateDataLight представляет облегчённую модель данных о корпоративном клиенте.
type CorporateDataLight struct {
	Email                *string                `json:"email,omitempty"`
	Name                 string                 `json:"name"`
	RegistrationNumber   *string                `json:"registrationNumber,omitempty"`
	IncorporationCountry *string                `json:"incorporationCountry,omitempty"`
	Address              Address                `json:"address"`
	IncorporationDate    *utils.ISOExtendedDate `json:"incorporationDate,omitempty"`
}

type UltimateBeneficialOwner struct {
	LastName                  string                `json:"lastName"`
	FirstName                 string                `json:"firstName"`
	BirthDate                 utils.ISOExtendedDate `json:"birthDate"`
	Ownership                 int                   `json:"ownership"`
	Document                  Document              `json:"document"`
	BeneficialOwnerPep        bool                  `json:"beneficialOwnerPep"`
	BeneficialOwnerPepDetails string                `json:"beneficialOwnerPepDetails"`
	UsaTaxResidency           bool                  `json:"usaTaxResidency"`
	GiinNumber                string                `json:"giinNumber"`
}

type OtherDetails struct {
	BusinessActivity    string  `json:"businessActivity"`
	RelevantInformation *string `json:"relevantInformation,omitempty"`
	NegativeInformation *string `json:"negativeInformation,omitempty"`
}

type BusinessPartner struct {
	Name                           string  `json:"name"`
	IncorporationCountryCode       string  `json:"incorporationCountryCode"`
	PlannedTransfersQuantityMonth  int     `json:"plannedTransfersQuantityMonth"`
	PlannedTransfersEurVolumeMonth float32 `json:"plannedTransfersEurVolumeMonth"`
	AdditionalInfo                 *string `json:"additionalInfo,omitempty"`
	BasisPartnership               string  `json:"basisPartnership"`
	Website                        *string `json:"website,omitempty"`
}

type FundFlows struct {
	PlannedIncTransfersQuantity  int     `json:"plannedIncTransfersQuantity"`
	PlannedIncTransfersEurVolume float32 `json:"plannedIncTransfersEurVolume"`
	PlannedOutTransfersQuantity  int     `json:"plannedOutTransfersQuantity"`
	PlannedOutTransfersEurVolume float32 `json:"plannedOutTransfersEurVolume"`
}

type ComplianceEvaluation struct {
	AmlRiskLevel      AmlRiskLevelEnum `json:"amlRiskLevel"`
	ReviewPeriodicity string           `json:"reviewPeriodicity"`
	AppliedLimits     string           `json:"appliedLimits"`
	AdditionalInfo    *string          `json:"additionalInfo,omitempty"`
}

// AddressData представляет информацию об адресе.
type AddressData struct {
	Country string  `json:"country"`
	State   *string `json:"state,omitempty"`
	Zip     string  `json:"zip"`
	City    string  `json:"city"`
	Street  string  `json:"street"`
}

// DocumentData представляет информацию о документе.
type DocumentData struct {
	Type              DocumentTypeEnum       `json:"type"`
	Number            string                 `json:"number"`
	IssuedCountryCode string                 `json:"issuedCountryCode"`
	IssuedBy          string                 `json:"issuedBy"`
	IssuedDate        utils.ISOExtendedDate  `json:"issuedDate"`
	ExpirationDate    *utils.ISOExtendedDate `json:"expirationDate,omitempty"`
}

type Client struct {
	ClientCustomerID *string             `json:"clientCustomerId,omitempty"`
	WalletUUID       *string             `json:"walletUuid,omitempty"`
	Individual       *IndividualData     `json:"individual,omitempty"`
	Corporate        *CorporateDataLight `json:"corporate,omitempty"`
}

type CustomInfo map[string]interface{}

type CustomFormat map[string]interface{}

type MyExampleObject struct {
	MyExampleParam2 *string `json:"MyExampleParam2,omitempty"`
	MyExampleParam3 *string `json:"MyExampleParam3,omitempty"`
}

type IndividualEntry struct {
	Phone      string   `json:"phone"`
	Email      string   `json:"email"`
	BirthDate  string   `json:"birthDate"`
	BirthPlace string   `json:"birthPlace"`
	Address    Address  `json:"address"`
	Document   Document `json:"document"`
	LastName   string   `json:"lastName"`
	FirstName  string   `json:"firstName"`
	MiddleName string   `json:"middleName"`
}

type Address struct {
	Country string  `json:"country"`
	State   *string `json:"state,omitempty"`
	Zip     string  `json:"zip"`
	City    string  `json:"city"`
	Street  string  `json:"street"`
}

type Document struct {
	Type              string                `json:"type"`
	Number            string                `json:"number"`
	IssuedCountryCode string                `json:"issuedCountryCode"`
	IssuedBy          string                `json:"issuedBy"`
	IssuedDate        utils.ISOExtendedDate `json:"issuedDate"`
	ExpirationDate    utils.ISOExtendedDate `json:"expirationDate"`
}

type PaymentDetails struct {
	Description    string            `json:"description"`
	PaymentMethod  PaymentMethodEnum `json:"paymentMethod"`
	PayeeRequisite Requisites        `json:"payeeRequisite"`
	PayerRequisite Requisites        `json:"payerRequisite"`
}

type Requisites struct {
	SortCode      *string `json:"sortCode,omitempty"`
	AccountNumber *string `json:"accountNumber,omitempty"`
	IBAN          *string `json:"iban,omitempty"`
	BankSwiftCode *string `json:"bankSwiftCode,omitempty"`
	Name          *string `json:"name,omitempty"`
}
