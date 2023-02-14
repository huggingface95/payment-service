package postgres

type User interface {
	StructName() string
	TableName() string
	Omit() []string
	MergeOmit(omits []string) []string
	HashPassword(password string) error
	CheckPassword(providedPassword string) error
	IsGoogle2FaSecret() bool
	InClientIpAddresses(ip string) bool
	GetBackupCodeDataAttribute() *BackupJson
	GetId() uint64
	GetFullName() string
	GetEmail() string
	IsActivated() bool
	IsEmailVerify() bool
	IsChangePassword() bool
	GetCompanyId() uint64
	GetTwoFactorAuthSettingId() uint64
	GetGoogle2FaSecret() string
	GetModelType() string
	SetIsActivated(v uint64)
	SetIsEmailVerify(v uint64)
	GetCompany() *Company
	SetBackupCodeData(v *BackupJson)
	SetGoogle2FaSecret(v string)
	SetTwoFactorAuthSettingId(v uint64)
	SetCompanyId(v uint64)
	SetNeedChangePassword(v bool)
}
