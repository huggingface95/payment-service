package queue

import "payment-service/utils"

type Address struct {
	Country string  `json:"country"`
	State   *string `json:"state,omitempty"`
	Zip     string  `json:"zip"`
	City    string  `json:"city"`
	Street  string  `json:"street"`
}

type Document struct {
	Type              DocumentTypeEnum       `json:"type"`
	Number            string                 `json:"number"`
	IssuedCountryCode string                 `json:"issued_country_code"`
	IssuedBy          string                 `json:"issued_by"`
	IssuedDate        utils.ISOExtendedDate  `json:"issued_date"`
	ExpirationDate    *utils.ISOExtendedDate `json:"expiration_date,omitempty"`
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
