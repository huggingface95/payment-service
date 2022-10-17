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

func GetOauthAccessTokenWithConditions(columns map[string]interface{}) *postgres.OauthAccessToken {
	var oauthAccess *postgres.OauthAccessToken
	query := database.PostgresInstance.
		Preload("Member").
		Preload("Individual").
		Preload("Client").
		Order("created_at desc").
		Limit(1)

	for column, value := range columns {
		query.Where(column+" = ?", value)
	}
	query.First(&oauthAccess)

	return oauthAccess
}

func CreateOauthAccessToken(oauthAccess *postgres.OauthAccessToken) *postgres.OauthAccessToken {
	result := database.PostgresInstance.Create(&oauthAccess)

	if result.Error != nil {
		return nil
	}

	return oauthAccess
}

func SaveOauthAccessToken(oauthAccess *postgres.OauthAccessToken) {
	database.PostgresInstance.Save(&oauthAccess)
	return
}
