package oauthRepository

import (
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
)

func GetOauthClientWithConditions(columns map[string]interface{}) *postgres.OauthClient {
	var oauthClient *postgres.OauthClient
	query := database.PostgresInstance.Limit(1)

	for column, value := range columns {
		query.Where(column+" = ?", value)
	}
	query.First(&oauthClient)

	return oauthClient
}

func GetOauthClientByType(provider string, jwtType string) *postgres.OauthClient {
	var condition map[string]interface{}
	if jwtType == constants.Personal {
		condition = map[string]interface{}{"provider": provider, "personal_access_client": true, "password_client": false}
	} else {
		condition = map[string]interface{}{"provider": provider, "personal_access_client": false, "password_client": true}
	}
	return GetOauthClientWithConditions(condition)
}
