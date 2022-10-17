package postgres

import (
	"time"
)

type OauthCode struct {
	Id        string `gorm:"column:id"`
	UserId    uint64 `gorm:"column:user_id"`
	ClientId  uint64 `gorm:"column:client_id"`
	Revoked   bool   `gorm:"column:revoked"`
	ExpiresAt time.Time
}

func (*OauthCode) TableName() string {
	return "oauth_auth_codes"
}
