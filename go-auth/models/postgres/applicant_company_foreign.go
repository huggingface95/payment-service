package postgres

type ApplicantIndividualCompany struct {
	ApplicantId        uint64 `gorm:"column:applicant_id"`
	ApplicantCompanyId uint64 `gorm:"column:applicant_company_id"`
	RelationId         uint64 `gorm:"column:applicant_individual_company_relation_id"`
	PositionId         uint64 `gorm:"column:applicant_individual_company_position_id"`
}

func (*ApplicantIndividualCompany) TableName() string {
	return "applicant_individual_company"
}
