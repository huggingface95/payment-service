package dto

import "time"

type CreateOauthTokenDto struct {
	Key           string
	Id            uint64
	OauthClientId uint64
	Token         string
	Provider      string
	OauthCodeTime time.Time
	AuthUserTime  time.Time
}

func (cr *CreateOauthTokenDto) Parse(
	k string, id uint64, cId uint64, t string, p string, oTime time.Time, aTime time.Time,
) *CreateOauthTokenDto {
	cr.Key = k
	cr.Id = id
	cr.OauthClientId = cId
	cr.Token = t
	cr.Provider = p
	cr.OauthCodeTime = oTime
	cr.AuthUserTime = aTime

	return cr
}
