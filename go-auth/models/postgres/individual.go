package postgres

import (
	"encoding/json"
	"golang.org/x/crypto/bcrypt"
	"gorm.io/datatypes"
	"jwt-authentication-golang/constants"
	"time"
)

type Individual struct {
	ID                       uint64              `gorm:"primarykey,column:id"`
	FirstName                string              `gorm:"column:first_name"`
	LastName                 string              `gorm:"column:last_name"`
	MiddleName               string              `gorm:"column:middle_name"`
	Email                    string              `gorm:"column:email"`
	Url                      string              `gorm:"column:url"`
	Phone                    string              `gorm:"column:phone"`
	CountryId                uint64              `gorm:"column:country_id"`
	CitizenshipCountryId     uint64              `gorm:"column:citizenship_country_id"`
	State                    string              `gorm:"column:state"`
	City                     string              `gorm:"column:city"`
	Address                  string              `gorm:"column:address"`
	Zip                      string              `gorm:"column:zip"`
	Nationality              string              `gorm:"column:nationality"`
	BirthCountryId           uint64              `gorm:"column:birth_country_id"`
	BirthState               string              `gorm:"column:birth_state"`
	BirthCity                string              `gorm:"column:birth_city"`
	BirthAt                  time.Time           `gorm:"column:birth_at"`
	Sex                      int8                `gorm:"column:sex"`
	PasswordHash             string              `gorm:"column:password_hash"`
	PasswordSalt             string              `gorm:"column:password_salt"`
	ProfileAdditionalFields  datatypes.JSON      `gorm:"column:profile_additional_fields"`
	PersonalAdditionalFields datatypes.JSON      `gorm:"column:personal_additional_fields"`
	ContactsAdditionalFields datatypes.JSON      `gorm:"column:contacts_additional_fields"`
	ApplicantStatusId        uint64              `gorm:"column:applicant_status_id"`
	ApplicantStateId         uint64              `gorm:"column:applicant_state_id"`
	IsVerificationPhone      bool                `gorm:"column:is_verification_phone"`
	FullName                 string              `gorm:"column:fullname"`
	CompanyId                uint64              `gorm:"column:company_id"`
	MemberGroupRoleId        uint64              `gorm:"column:member_group_role_id"`
	TwoFactorAuthId          uint64              `gorm:"column:two_factor_auth_setting_id"`
	ApplicantStateReasonId   uint64              `gorm:"column:applicant_state_reason_id"`
	ApplicantRiskLevelId     uint64              `gorm:"column:applicant_risk_level_id"`
	AccountManagerMemberId   uint64              `gorm:"column:account_manager_member_id"`
	LanguageId               uint64              `gorm:"column:language_id"`
	CreatedAt                time.Time           `gorm:"column:created_at"`
	UpdatedAt                time.Time           `gorm:"column:updated_at"`
	IsVerificationEmail      bool                `gorm:"is_verification_email"`
	Google2FaSecret          string              `gorm:"column:google2fa_secret"`
	IsActive                 bool                `gorm:"column:is_active"`
	TwoFactorAuthSettingId   uint64              `gorm:"column:two_factor_auth_setting_id"`
	BackupCodeData           *BackupJson         `gorm:"column:backup_codes"`
	ClientIpAddresses        []*ClientIpAddress  `gorm:"foreignKey:ClientId;references:ID"`
	OauthAccessTokens        []*OauthAccessToken `gorm:"foreignKey:UserId;references:ID"`
	Company                  *Company            `gorm:"foreignKey:CompanyId"`
}

func (*Individual) TableName() string {
	return "applicant_individual"
}

func (user *Individual) HashPassword(password string) error {
	bytes, err := bcrypt.GenerateFromPassword([]byte(password), 14)
	if err != nil {
		return err
	}
	user.PasswordHash = string(bytes)
	return nil
}

func (user *Individual) CheckPassword(providedPassword string) error {
	err := bcrypt.CompareHashAndPassword([]byte(user.PasswordHash), []byte(providedPassword))
	if err != nil {
		return err
	}
	return nil
}

func (user *Individual) IsGoogle2FaSecret() bool {
	return len(user.Google2FaSecret) > 0
}

func (user *Individual) InClientIpAddresses(ip string) bool {
	for _, clientIp := range user.ClientIpAddresses {
		if clientIp.IpAddress == ip {
			return true
		}
	}
	return false
}

func (user *Individual) GetBackupCodeDataAttribute() *BackupJson {
	var backupJson *BackupJson

	value, err := user.BackupCodeData.MarshalJSON()
	if err != nil {
		return nil
	}

	err = json.Unmarshal(value, &backupJson)
	if err != nil {
		return nil
	}

	return backupJson
}

func (user *Individual) GetId() uint64 {
	return user.ID
}

func (user *Individual) GetFullName() string {
	return user.FullName
}

func (user *Individual) GetEmail() string {
	return user.Email
}

func (user *Individual) IsActivated() bool {
	return user.IsActive
}

func (user *Individual) IsEmailVerify() bool {
	return user.IsVerificationEmail
}

func (user *Individual) GetCompanyId() uint64 {
	return user.CompanyId
}

func (user *Individual) GetCompany() *Company {
	if user.Company == nil {
		return &Company{}
	}
	return user.Company
}

func (user *Individual) GetTwoFactorAuthSettingId() uint64 {
	return user.TwoFactorAuthSettingId
}

func (user *Individual) GetGoogle2FaSecret() string {
	return user.Google2FaSecret
}

func (user *Individual) GetModelType() string {
	return constants.Individual
}

func (user *Individual) SetIsActivated(v bool) {
	user.IsActive = v
}

func (user *Individual) SetIsEmailVerify(v bool) {
	user.IsVerificationEmail = v
}

func (user *Individual) SetBackupCodeData(v *BackupJson) {
	user.BackupCodeData = v
}

func (user *Individual) SetGoogle2FaSecret(v string) {
	user.Google2FaSecret = v
}

func (user *Individual) SetTwoFactorAuthSettingId(v uint64) {
	user.TwoFactorAuthSettingId = v
}
