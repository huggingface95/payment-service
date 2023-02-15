package postgres

type Company struct {
	Id                          uint64 `gorm:"primarykey,column:id"`
	Name                        string `gorm:"column:name"`
	BackofficeForgotPasswordUrl string `gorm:"column:backoffice_forgot_password_url"`
}

func (*Company) TableName() string {
	return "companies"
}
