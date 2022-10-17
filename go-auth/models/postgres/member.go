package postgres

import (
	"encoding/json"
	"golang.org/x/crypto/bcrypt"
	"gorm.io/datatypes"
	"jwt-authentication-golang/constants"
	"time"
)

type Member struct {
	ID                     uint64 `gorm:"primarykey,column:id"`
	CompanyId              uint64 `gorm:"column:company_id"`
	FirstName              string `gorm:"column:first_name"`
	LastName               string `gorm:"column:last_name"`
	FullName               string `gorm:"column:fullname"`
	Email                  string `gorm:"unique,column:email"`
	PasswordHash           string `gorm:"column:password_hash"`
	IsActive               bool   `gorm:"column:is_active"`
	TwoFactorAuthSettingId uint64 `gorm:"column:two_factor_auth_setting_id"`
	Google2FaSecret        string `gorm:"column:google2fa_secret"`
	IsVerificationEmail    bool   `gorm:"column:is_verification_email"`
	CreatedAt              time.Time
	UpdatedAt              time.Time
	BackupCodeData         *BackupJson         `gorm:"column:backup_codes"`
	ClientIpAddresses      []*ClientIpAddress  `gorm:"foreignKey:ClientId;references:ID"`
	OauthAccessTokens      []*OauthAccessToken `gorm:"foreignKey:UserId;references:ID"`
	Company                *Company            `gorm:"foreignKey:CompanyId"`
}

type BackupJson struct {
	datatypes.JSON
	BackupCodes []struct {
		Use  bool   `json:"use"`
		Code string `json:"code"`
	} `json:"backup_codes"`
}

func (*Member) TableName() string {
	return "members"
}

func (user *Member) HashPassword(password string) error {
	bytes, err := bcrypt.GenerateFromPassword([]byte(password), 14)
	if err != nil {
		return err
	}
	user.PasswordHash = string(bytes)
	return nil
}

func (user *Member) CheckPassword(providedPassword string) error {
	err := bcrypt.CompareHashAndPassword([]byte(user.PasswordHash), []byte(providedPassword))
	if err != nil {
		return err
	}
	return nil
}

func (user *Member) IsGoogle2FaSecret() bool {
	return len(user.Google2FaSecret) > 0
}

func (user *Member) InClientIpAddresses(ip string) bool {
	for _, clientIp := range user.ClientIpAddresses {
		if clientIp.IpAddress == ip {
			return true
		}
	}
	return false
}

func (user *Member) GetBackupCodeDataAttribute() *BackupJson {
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

func (user *Member) GetId() uint64 {
	return user.ID
}

func (user *Member) GetFullName() string {
	return user.FullName
}

func (user *Member) GetEmail() string {
	return user.Email
}

func (user *Member) IsActivated() bool {
	return user.IsActive
}

func (user *Member) IsEmailVerify() bool {
	return user.IsVerificationEmail
}

func (user *Member) GetCompanyId() uint64 {
	return user.CompanyId
}

func (user *Member) GetCompany() *Company {
	if user.Company == nil {
		return &Company{}
	}
	return user.Company
}

func (user *Member) GetTwoFactorAuthSettingId() uint64 {
	return user.TwoFactorAuthSettingId
}

func (user *Member) GetGoogle2FaSecret() string {
	return user.Google2FaSecret
}

func (user *Member) GetModelType() string {
	return constants.Member
}

func (user *Member) SetIsActivated(v bool) {
	user.IsActive = v
}

func (user *Member) SetIsEmailVerify(v bool) {
	user.IsVerificationEmail = v
}

func (user *Member) SetBackupCodeData(v *BackupJson) {
	user.BackupCodeData = v
}

func (user *Member) SetGoogle2FaSecret(v string) {
	user.Google2FaSecret = v
}

func (user *Member) SetTwoFactorAuthSettingId(v uint64) {
	user.TwoFactorAuthSettingId = v
}
