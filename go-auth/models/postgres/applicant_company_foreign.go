package postgres

type ApplicantCompanyForeign struct {
	IndividualId uint64 `gorm:"column:applicant_id"`
	CompanyId    uint64 `gorm:"column:applicant_company_id"`
	RelationId   uint64 `gorm:"column:applicant_individual_company_relation_id"`
	PositionId   uint64 `gorm:"column:applicant_individual_company_position_id"`
}

func (*ApplicantCompanyForeign) TableName() string {
	return "applicant_individual_company"
}
