package dto

import "time"

type CreateOauthTokenDto struct {
	Key           string
	Id            uint64
	OauthClientId uint64
	Provider      string
	OauthCodeTime time.Time
	AuthUserTime  time.Time
}
