package postgres

import (
	"time"
)

type OauthClient struct {
	Id                   uint64 `gorm:"column:id"`
	UserId               uint64 `gorm:"column:user_id"`
	Name                 string `gorm:"column:name"`
	Secret               string `gorm:"column:secret"`
	Provider             string `gorm:"column:provider"`
	Redirect             string `gorm:"column:redirect"`
	PersonalAccessClient bool   `gorm:"column:personal_access_client"`
	PasswordClient       bool   `gorm:"column:password_client"`
	Revoked              bool   `gorm:"column:revoked"`
	CreatedAt            time.Time
	UpdatedAt            time.Time
}

func (*OauthClient) TableName() string {
	return "oauth_clients"
}
