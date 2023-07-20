package postgres

import (
	"jwt-authentication-golang/constants"
	"reflect"
	"time"
)

type ApplicantCompany struct {
	Id                  uint64       `gorm:"primarykey,column:id"`
	Email               string       `gorm:"column:email"`
	Name                string       `gorm:"column:name"`
	Url                 string       `gorm:"column:url"`
	IsActive            uint64       `gorm:"column:applicant_state_id"`
	IsVerificationEmail uint64       `gorm:"column:email_verification_status_id"`
	CompanyId           uint64       `gorm:"column:company_id"`
	CountryId           *uint64      `gorm:"column:country_id"`
	CreatedAt           time.Time    `gorm:"column:created_at"`
	UpdatedAt           time.Time    `gorm:"column:updated_at"`
	Company             *Company     `gorm:"foreignKey:CompanyId"`
	Individual          []Individual `gorm:"many2many:applicant_individual_company;foreignKey:Id;joinForeignKey:ApplicantCompanyId;References:Id;joinReferences:ApplicantId"`
}

func (*ApplicantCompany) TableName() string {
	return "applicant_companies"
}

func (user *ApplicantCompany) StructName() string {
	rModel := reflect.TypeOf(user)

	return rModel.Elem().Name()
}

func (user *ApplicantCompany) ClientType() string {
	return constants.Corporate
}

func (user *ApplicantCompany) GetId() uint64 {
	return user.Id
}

func (user *ApplicantCompany) GetFullName() string {
	return user.Name
}

func (user *ApplicantCompany) GetEmail() string {
	return user.Email
}

func (*ApplicantCompany) Omit() []string {
	return []string{}
}

func (user *ApplicantCompany) MergeOmit(omits []string) []string {
	var list []string

	return list
}

func (user *ApplicantCompany) HashPassword(password string) error {
	return nil
}

func (user *ApplicantCompany) CheckPassword(providedPassword string) error {
	return nil
}

func (user *ApplicantCompany) IsGoogle2FaSecret() bool {
	return len(user.Individual[0].Google2FaSecret) > 0
}

func (user *ApplicantCompany) InClientIpAddresses(ip string) bool {
	return false
}

func (user *ApplicantCompany) GetBackupCodeDataAttribute() []BackupCodes {
	var codes []BackupCodes
	return codes
}

func (user *ApplicantCompany) GetCreatedAt() time.Time {
	return user.CreatedAt
}

func (user *ApplicantCompany) IsActivated() bool {
	return user.IsActive == ApplicantStateActive
}

func (user *ApplicantCompany) IsEmailVerify() bool {
	return user.IsVerificationEmail == ApplicantVerificationVerifyed
}

func (user *ApplicantCompany) IsChangePassword() bool {
	return false
}

func (user *ApplicantCompany) GetCompanyId() uint64 {
	return user.CompanyId
}

func (user *ApplicantCompany) GetCompany() *Company {
	if user.Company == nil {
		return &Company{}
	}
	return user.Company
}

func (user *ApplicantCompany) GetTwoFactorAuthSettingId() uint64 {
	return user.Individual[0].TwoFactorAuthSettingId
}

func (user *ApplicantCompany) GetGoogle2FaSecret() string {
	return user.Individual[0].Google2FaSecret
}

func (user *ApplicantCompany) GetModelType() string {
	return constants.Corporate
}

func (user *ApplicantCompany) SetIsActivated(v uint64) {
	user.IsActive = v
}

func (user *ApplicantCompany) SetCompanyId(v uint64) {
	user.CompanyId = v
}

func (user *ApplicantCompany) SetNeedChangePassword(v bool) {

}

func (user *ApplicantCompany) SetIsEmailVerify(v uint64) {
	user.IsVerificationEmail = v
}

func (user *ApplicantCompany) SetBackupCodeData(v []BackupCodes) {

}

func (user *ApplicantCompany) SetGoogle2FaSecret(v string) {

}

func (user *ApplicantCompany) SetTwoFactorAuthSettingId(v uint64) {

}
