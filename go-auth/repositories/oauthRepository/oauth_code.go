package oauthRepository

import (
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
)

func GetOauthCodeWithConditions(columns map[string]interface{}) *postgres.OauthCode {
	var oauthCode *postgres.OauthCode
	query := database.PostgresInstance.Order("expires_at desc").Limit(1)

	for column, value := range columns {
		query.Where(column+" = ?", value)
	}
	query.First(&oauthCode)

	return oauthCode
}
