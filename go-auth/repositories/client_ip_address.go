package repositories

import (
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
)

func CreateClientIpAddress(ip string, clientId uint64, clientType string) *postgres.ClientIpAddress {
	var code = &postgres.ClientIpAddress{
		IpAddress:  ip,
		ClientId:   clientId,
		ClientType: clientType,
	}

	result := database.PostgresInstance.Create(&code)
	if result.Error != nil {
		return nil
	}

	return code
}
