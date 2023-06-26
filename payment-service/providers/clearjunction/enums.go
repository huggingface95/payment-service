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
	StatusAccepted  StatusEnum = "accepted"
	StatusPending   StatusEnum = "pending"
	StatusAllocated StatusEnum = "allocated"
	StatusDeclined  StatusEnum = "declined"
	StatusCreated   StatusEnum = "created"
)

// DocumentTypeEnum представляет тип документа.
type DocumentTypeEnum string

// AmlRiskLevelEnum представляет уровень риска AML
type AmlRiskLevelEnum string

// StatusEnum представляет статус запроса
type StatusEnum string
