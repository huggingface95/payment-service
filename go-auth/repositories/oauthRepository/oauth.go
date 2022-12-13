package oauthRepository

import (
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/postgres"
	"time"
)

func CreateOauthCode(userId uint64, clientId uint64, revoked bool, expires time.Time) *postgres.OauthCode {
	var code = &postgres.OauthCode{
		Id:        helpers.GenerateRandomString(8),
		UserId:    userId,
		ClientId:  clientId,
		Revoked:   revoked,
		ExpiresAt: expires,
	}

	result := database.PostgresInstance.Create(&code)
	if result.Error != nil {
		return nil
	}

	return code
}
