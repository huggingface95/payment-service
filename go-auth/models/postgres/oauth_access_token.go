package postgres

import (
	"jwt-authentication-golang/constants"
	"time"
)

type OauthAccessToken struct {
	ID                string       `gorm:"primarykey,column:id"`
	UserId            uint64       `gorm:"column:user_id"`
	ClientId          uint64       `gorm:"column:client_id"`
	Name              string       `gorm:"column:name"`
	Scopes            string       `gorm:"column:scopes"`
	Revoked           bool         `gorm:"column:revoked"`
	TwoFactorVerified bool         `gorm:"column:twofactor_verified"`
	Member            Member       `gorm:"foreignKey:UserId"`
	Individual        Individual   `gorm:"foreignKey:UserId"`
	Client            *OauthClient `gorm:"foreignKey:ClientId"`
	CreatedAt         time.Time
	UpdatedAt         time.Time
	ExpiresAt         time.Time
}

func (*OauthAccessToken) TableName() string {
	return "oauth_access_tokens"
}

func (o *OauthAccessToken) GetUser() User {
	if o.Client.Provider == constants.Individual {
		return convertToInterface(o.Individual)
	}
	return convertToInterface(o.Member)
}

func convertToInterface(i interface{}) User {
	return i.(User)
}
