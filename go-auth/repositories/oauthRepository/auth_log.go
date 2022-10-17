package oauthRepository

import (
	"github.com/google/uuid"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/models/clickhouse"
)

func GetAuthLogWithConditions(columns map[string]string) *clickhouse.AuthenticationLog {
	var authLog *clickhouse.AuthenticationLog
	query := database.ClickhouseInstance.
		Order("created_at desc").
		Limit(1)

	for column, value := range columns {
		query.Where(column+" = ?", value)
	}
	query.First(&authLog)

	return authLog
}

func InsertAuthLog(status string, dto *dto.CreateAuthLogDto) *clickhouse.AuthenticationLog {
	authLog := &clickhouse.AuthenticationLog{
		Id:             uuid.NewString(),
		Provider:       dto.Provider,
		Email:          dto.Email,
		Status:         status,
		Platform:       dto.DeviceInfo.OsPlatform,
		DeviceType:     dto.DeviceInfo.Type,
		Model:          dto.DeviceInfo.Model,
		Company:        dto.Company,
		Browser:        dto.DeviceInfo.ClientEngine,
		BrowserVersion: dto.DeviceInfo.ClientEngineVersion,
		Ip:             dto.DeviceInfo.Ip,
		Domain:         dto.DeviceInfo.Domain,
		Country:        dto.DeviceInfo.Country,
		City:           dto.DeviceInfo.City,
	}

	result := database.ClickhouseInstance.Omit("ExpiredAt").Create(&authLog)

	if result.Error != nil {
		return nil
	}

	return authLog
}
