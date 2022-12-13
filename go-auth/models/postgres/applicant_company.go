package postgres

type ApplicantCompany struct {
	ID    uint64 `gorm:"primarykey,column:id"`
	Email string `gorm:"column:email"`
	Name  string `gorm:"column:name"`
	Url   string `gorm:"column:url"`
}

func (*ApplicantCompany) TableName() string {
	return "applicant_companies"
}
