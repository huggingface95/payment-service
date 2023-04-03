package oauthRepository

import (
	"gorm.io/gorm"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
)

func getOauthClientWithConditions(columns map[string]interface{}) *gorm.DB {
	query := database.PostgresInstance.Model(&postgres.OauthClient{})

	for column, value := range columns {
		query.Where(column+" = ?", value)
	}

	return query
}

func GetFirst(columns map[string]interface{}) *postgres.OauthClient {
	var oauthClient *postgres.OauthClient
	query := getOauthClientWithConditions(columns).Limit(1)
	query.First(&oauthClient)

	return oauthClient
}

func Get(columns map[string]interface{}) []postgres.OauthClient {
	var oauthClients []postgres.OauthClient
	query := getOauthClientWithConditions(columns)

	query.Find(&oauthClients)

	return oauthClients
}

func GetOauthClientByType(provider string, jwtType string) *postgres.OauthClient {
	var condition map[string]interface{}
	if jwtType == constants.Personal {
		condition = map[string]interface{}{"provider": provider, "personal_access_client": true, "password_client": false}
	} else {
		condition = map[string]interface{}{"provider": provider, "personal_access_client": false, "password_client": true}
	}
	return GetFirst(condition)
}

func GetOauthClients(jwtType string) map[string]postgres.OauthClient {
	var condition map[string]interface{}
	var clients = make(map[string]postgres.OauthClient)
	if jwtType == constants.Personal {
		condition = map[string]interface{}{"personal_access_client": true, "password_client": false}
	} else {
		condition = map[string]interface{}{"personal_access_client": false, "password_client": true}
	}

	for _, v := range Get(condition) {
		clients[v.Provider] = v
	}

	return clients
}
