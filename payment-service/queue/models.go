package queue

import "payment-service/utils"

type Applicant struct {
	Individual *ApplicantIndividual `json:"individual,omitempty"`
	Company    *ApplicantCompany    `json:"company,omitempty"`
}

type ApplicantLite struct {
	Individual *ApplicantIndividual  `json:"applicant_individual,omitempty"`
	Company    *ApplicantCompanyLite `json:"applicant_company,omitempty"`
}

type ApplicantIndividual struct {
	Phone      *string               `json:"phone,omitempty"`
	Email      *string               `json:"email,omitempty"`
	BirthAt    utils.ISOExtendedDate `json:"birth_at"`
	BirthPlace *string               `json:"birth_place,omitempty"`
	LastName   string                `json:"last_name"`
	FirstName  string                `json:"first_name"`
	MiddleName *string               `json:"middle_name,omitempty"`
	Address    Address               `json:"address"`
	Document   Document              `json:"document"`
}

type ApplicantCompany struct {
	Email                   *string                   `json:"email,omitempty"`
	Name                    string                    `json:"name"`
	RegistrationNumber      string                    `json:"registration_number"`
	IncorporationCountry    string                    `json:"incorporation_country"`
	Address                 Address                   `json:"address"`
	IncorporationDate       utils.ISOExtendedDate     `json:"incorporation_date"`
	UltimateBeneficialOwner []UltimateBeneficialOwner `json:"ultimate_beneficial_owner"`
	TradingWebsite          *string                   `json:"trading_website,omitempty"`
	ExpectedTurnover        int                       `json:"expected_turnover"`
	BeneficialLegalEntity   *string                   `json:"beneficial_legal_entity,omitempty"`
	OtherDetails            OtherDetails              `json:"other_details"`
	BusinessPartners        []BusinessPartner         `json:"business_partners"`
	FundFlows               FundFlows                 `json:"fund_flows"`
	ComplianceEvaluation    ComplianceEvaluation      `json:"compliance_evaluation"`
}

type ApplicantCompanyLite struct {
	Email                *string                `json:"email,omitempty"`
	Name                 string                 `json:"name"`
	RegistrationNumber   *string                `json:"registration_number,omitempty"`
	IncorporationCountry *string                `json:"incorporation_country,omitempty"`
	Address              Address                `json:"address"`
	IncorporationDate    *utils.ISOExtendedDate `json:"incorporation_date,omitempty"`
}
