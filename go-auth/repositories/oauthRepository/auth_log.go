package oauthRepository

import (
	"github.com/google/uuid"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/models/clickhouse"
	"time"
)

func HasAuthLogWithConditions(email string, clientType string, deviceInfo *dto.DeviceDetectorInfo) (authLog *clickhouse.AuthenticationLog) {
	code := encodeCode(clientType, email, deviceInfo)
	query := database.ClickhouseInstance.
		Order("created_at desc").
		Limit(1).
		Where("code = ?", code).
		Where("expired_at > ?", time.Now())

	exists := query.Find(&authLog)

	if exists.RowsAffected > 0 {
		return authLog
	}

	return nil
}

func InsertAuthLog(provider string, email string, companyName string, status string, expirationJWTTime time.Time, deviceInfo *dto.DeviceDetectorInfo) *clickhouse.AuthenticationLog {
	authLog := &clickhouse.AuthenticationLog{
		Id:             uuid.NewString(),
		Company:        companyName,
		Status:         status,
		ExpiredAt:      expirationJWTTime,
		Code:           encodeCode(provider, email, deviceInfo),
		Email:          email,
		Provider:       provider,
		Platform:       deviceInfo.OsPlatform,
		DeviceType:     deviceInfo.Type,
		Model:          deviceInfo.Model,
		Browser:        deviceInfo.ClientEngine,
		Ip:             deviceInfo.Ip,
		BrowserVersion: deviceInfo.ClientEngineVersion,
		Country:        deviceInfo.Country,
		City:           deviceInfo.City,
		Lang:           deviceInfo.Lang,
	}

	result := database.ClickhouseInstance.Create(&authLog)

	if result.Error != nil {
		return nil
	}

	return authLog
}
