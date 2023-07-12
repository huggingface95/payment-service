package queue

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

type DocumentTypeEnum string

type AmlRiskLevelEnum string
