package clearjunction

const (
	DocumentTypePassport      DocumentTypeEnum = "passport"
	DocumentTypeDriverLicense DocumentTypeEnum = "driverLicense"
	DocumentTypeIDCard        DocumentTypeEnum = "idCard"
	DocumentTypeOther         DocumentTypeEnum = "other"
)

const (
	AmlRiskLevelLow    AmlRiskLevelEnum = "Low"
	AmlRiskLevelMedium AmlRiskLevelEnum = "Medium"
	AmlRiskLevelHigh   AmlRiskLevelEnum = "High"
)

const (
	IBANStatusAccepted  IBANStatusEnum = "accepted"
	IBANStatusPending   IBANStatusEnum = "pending"
	IBANStatusAllocated IBANStatusEnum = "allocated"
	IBANStatusDeclined  IBANStatusEnum = "declined"
)

const (
	PayInStatusCreated    PayInStatusEnum = "created"
	PayInStatusExpired    PayInStatusEnum = "expired"
	PayInStatusCanceled   PayInStatusEnum = "canceled"
	PayInStatusRejected   PayInStatusEnum = "rejected"
	PayInStatusReturned   PayInStatusEnum = "returned"
	PayInStatusPending    PayInStatusEnum = "pending"
	PayInStatusAuthorized PayInStatusEnum = "authorized"
	PayInStatusCaptured   PayInStatusEnum = "captured"
	PayInStatusSettled    PayInStatusEnum = "settled"
	PayInStatusDeclined   PayInStatusEnum = "declined"
)

const (
	PayOutStatusCreated  PayOutStatusEnum = "created"
	PayOutStatusCanceled PayOutStatusEnum = "canceled"
	PayOutStatusPending  PayOutStatusEnum = "pending"
	PayOutStatusSettled  PayOutStatusEnum = "settled"
	PayOutStatusDeclined PayOutStatusEnum = "declined"
)

const (
	PaymentMethodEU  PaymentMethodEnum = "BankTransferEu"
	PaymentMethodFPS PaymentMethodEnum = "BankTransferFps"
)

// DocumentTypeEnum представляет тип документа.
type DocumentTypeEnum string

// AmlRiskLevelEnum представляет уровень риска AML
type AmlRiskLevelEnum string

// IBANStatusEnum представляет статус запроса
type IBANStatusEnum string

// PayInStatusEnum представляет статус входящего платежа
type PayInStatusEnum string

// PayOutStatusEnum представляет статус исходящего платежа
type PayOutStatusEnum string

// PaymentMethodEnum представляет модель типа платежа
type PaymentMethodEnum string
