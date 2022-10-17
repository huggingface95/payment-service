package oauthRepository

import (
	"github.com/google/uuid"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/clickhouse"
)

func GetActiveSessionWithConditions(columns map[string]string) *clickhouse.ActiveSession {
	var authLog *clickhouse.ActiveSession
	query := database.ClickhouseInstance.
		Order("created_at desc").
		Limit(1)

	for column, value := range columns {
		query.Where(column+" = ?", value)
	}
	query.First(&authLog)

	return authLog
}

func InsertActiveSessionLog(provider string, email string, active bool, trusted bool, deviceInfo *dto.DeviceDetectorInfo) *clickhouse.ActiveSession {
	activeSession := &clickhouse.ActiveSession{
		Id:             uuid.NewString(),
		Cookie:         helpers.GenerateRandomString(16),
		Email:          email,
		Provider:       provider,
		Active:         active,
		Trusted:        trusted,
		Platform:       deviceInfo.OsPlatform,
		DeviceType:     deviceInfo.Type,
		Model:          deviceInfo.Model,
		Browser:        deviceInfo.ClientEngine,
		Ip:             deviceInfo.Ip,
		BrowserVersion: deviceInfo.ClientEngineVersion,
		Country:        deviceInfo.Country,
		City:           deviceInfo.City,
	}

	result := database.ClickhouseInstance.Create(&activeSession)

	if result.Error != nil {
		return nil
	}

	return activeSession
}
