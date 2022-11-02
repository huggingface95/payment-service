package postgres

type CompanySetting struct {
	Id                   uint64     `gorm:"primarykey,column:id"`
	CompanyId            uint64     `gorm:"column:company_id"`
	ClientUrl            string     `gorm:"column:client_url"`
	EmailJwt             string     `gorm:"column:email_jwt"`
	SupportEmail         string     `gorm:"column:support_email"`
	ShowOwnLogo          bool       `gorm:"column:show_own_logo"`
	VvToken              string     `gorm:"column:vv_token"`
	LogoId               uint64     `gorm:"column:logo_id"`
	LoginUrl             string     `gorm:"column:login_url"`
	ApplicantGroupRoleId uint64     `gorm:"column:applicant_group_role_id"`
	GroupRole            *GroupRole `gorm:"foreignKey:ApplicantGroupRoleId"`
}

func (*CompanySetting) TableName() string {
	return "company_settings"
}
