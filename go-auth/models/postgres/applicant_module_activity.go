package postgres

type ApplicantModuleActivity struct {
	Id            uint64 `gorm:"primarykey,column:id"`
	ApplicantId   uint64 `gorm:"column:applicant_id"`
	ApplicantType string `gorm:"column:applicant_type"`
	ModuleId      uint64 `gorm:"column:module_id"`
	IsActive      bool   `gorm:"column:is_active"`
}

func (*ApplicantModuleActivity) TableName() string {
	return "applicant_module_activity"
}
