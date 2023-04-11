package oauthRepository

import (
	"encoding/base64"
	"fmt"
	"github.com/google/uuid"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/models/clickhouse"
	"time"
)

func HasActiveSessionWithConditions(email string, clientType string, deviceInfo *dto.DeviceDetectorInfo) (activeSession *clickhouse.ActiveSession) {
	code := encodeCode(clientType, email, deviceInfo)
	query := database.ClickhouseInstance.
		Order("created_at desc").
		Limit(1).
		Where("code = ?", code).
		Where("active = ?", true).
		Where("trusted = ?", true)
	exists := query.Find(&activeSession)

	if exists.RowsAffected > 0 {
		return activeSession
	}

	return nil
}

func encodeCode(provider string, email string, deviceInfo *dto.DeviceDetectorInfo) string {
	return base64.StdEncoding.EncodeToString([]byte(fmt.Sprintf("%s-%s-%s-%s-%s-%s", provider, email, deviceInfo.Ip, deviceInfo.OsName, deviceInfo.ClientEngine, deviceInfo.Lang)))
}

func InsertActiveSessionLog(provider string, email string, active bool, trusted bool, expiredAt *time.Time, deviceInfo *dto.DeviceDetectorInfo) *clickhouse.ActiveSession {
	activeSession := &clickhouse.ActiveSession{
		Id:             uuid.NewString(),
		Code:           encodeCode(provider, email, deviceInfo),
		Email:          email,
		Provider:       provider,
		Active:         active,
		Trusted:        trusted,
		Platform:       deviceInfo.OsName,
		DeviceType:     deviceInfo.Type,
		Model:          deviceInfo.Model,
		Browser:        deviceInfo.ClientName,
		Ip:             deviceInfo.Ip,
		BrowserVersion: deviceInfo.ClientEngineVersion,
		Country:        deviceInfo.Country,
		City:           deviceInfo.City,
		Lang:           deviceInfo.Lang,
		ExpiredAt:      expiredAt,
	}

	result := database.ClickhouseInstance.Create(&activeSession)
	if result.Error != nil {
		return nil
	}

	return activeSession
}
