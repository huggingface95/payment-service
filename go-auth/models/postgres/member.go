package postgres

import (
	"encoding/json"
	"golang.org/x/crypto/bcrypt"
	"gorm.io/datatypes"
	"jwt-authentication-golang/constants"
	"reflect"
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
	IsActive               uint64 `gorm:"column:member_status_id"`
	TwoFactorAuthSettingId uint64 `gorm:"column:two_factor_auth_setting_id"`
	Google2FaSecret        string `gorm:"column:google2fa_secret"`
	IsVerificationEmail    uint64 `gorm:"column:email_verification"`
	IsNeedChangePassword   bool   `gorm:"column:is_need_change_password"`
	CreatedAt              time.Time
	UpdatedAt              time.Time
	BackupCodes            datatypes.JSON     `gorm:"column:backup_codes"`
	ClientIpAddresses      []*ClientIpAddress `gorm:"foreignKey:ClientId;references:ID"`
	Company                *Company           `gorm:"foreignKey:CompanyId"`
}

type BackupCodes struct {
	Use  bool   `json:"use"`
	Code string `json:"code"`
}

func (user *Member) StructName() string {
	rModel := reflect.TypeOf(user)

	return rModel.Elem().Name()
}

func (user *Member) ClientType() string {
	return constants.Member
}

func (*Member) TableName() string {
	return "members"
}

func (*Member) Omit() []string {
	return []string{"fullname"}
}

func (user *Member) MergeOmit(omits []string) []string {
	var list []string
	baseOmits := user.Omit()

	for i := 0; i < len(baseOmits); i++ {
		list = append(list, baseOmits[i])
	}
	for i := 0; i < len(omits); i++ {
		list = append(list, omits[i])
	}

	return list
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
	return bcrypt.CompareHashAndPassword([]byte(user.PasswordHash), []byte(providedPassword))
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

func (user *Member) GetBackupCodeDataAttribute() []BackupCodes {
	var codes []BackupCodes

	str := user.BackupCodes.String()
	err := json.Unmarshal([]byte(str), &codes)

	if err != nil {
		return nil
	}

	return codes
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
	return user.IsActive == MemberStatusActive
}

func (user *Member) IsEmailVerify() bool {
	return user.IsVerificationEmail == MemberVerificationStatusVerified
}

func (user *Member) IsChangePassword() bool {
	return user.IsNeedChangePassword
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

func (user *Member) SetCompanyId(v uint64) {
	user.CompanyId = v
}

func (user *Member) SetNeedChangePassword(v bool) {
	user.IsNeedChangePassword = v
}

func (user *Member) SetIsActivated(v uint64) {
	user.IsActive = v
}

func (user *Member) SetIsEmailVerify(v uint64) {
	user.IsVerificationEmail = v
}

func (user *Member) SetBackupCodeData(v []BackupCodes) {
	data, err := json.Marshal(v)
	if err != nil {
		return
	}
	user.BackupCodes = data
}

func (user *Member) SetGoogle2FaSecret(v string) {
	user.Google2FaSecret = v
}

func (user *Member) SetTwoFactorAuthSettingId(v uint64) {
	user.TwoFactorAuthSettingId = v
}
