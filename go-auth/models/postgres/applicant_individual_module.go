package postgres

type ApplicantIndividualModule struct {
	ApplicantIndividualId uint64 `gorm:"column:applicant_individual_id"`
	ModuleId              uint64 `gorm:"column:module_id"`
	IsActive              bool   `gorm:"column:is_active"`
}

func (*ApplicantIndividualModule) TableName() string {
	return "applicant_individual_modules"
}
