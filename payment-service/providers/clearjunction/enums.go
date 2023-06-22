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

// DocumentTypeEnum представляет тип документа.
type DocumentTypeEnum string

type AmlRiskLevelEnum string
