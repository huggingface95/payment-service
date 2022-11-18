package oauthRepository

import (
	"encoding/base64"
	"fmt"
	"github.com/google/uuid"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/models/clickhouse"
)

func GetActiveSessionWithConditions(email string, deviceInfo *dto.DeviceDetectorInfo) *clickhouse.ActiveSession {
	var authLog *clickhouse.ActiveSession
	code := encodeCode(email, deviceInfo)
	query := database.ClickhouseInstance.
		Order("created_at desc").
		Limit(1).Where("code = ?", code)
	query.First(&authLog)

	return authLog
}

func HasActiveSessionWithConditions(email string, deviceInfo *dto.DeviceDetectorInfo) (bool, error) {
	var authLog *clickhouse.ActiveSession
	code := encodeCode(email, deviceInfo)
	query := database.ClickhouseInstance.
		Order("created_at desc").
		Limit(1).Where("code = ?", code)
	exists := query.Find(&authLog)

	ok := false
	if exists.RowsAffected > 0 {
		ok = true
	}

	return ok, exists.Error
}

func encodeCode(email string, deviceInfo *dto.DeviceDetectorInfo) string {
	return base64.StdEncoding.EncodeToString([]byte(fmt.Sprintf("%s-%s-%s-%s-%s", email, deviceInfo.Ip, deviceInfo.OsName, deviceInfo.ClientEngine, deviceInfo.Lang)))
}

func InsertActiveSessionLog(provider string, email string, active bool, trusted bool, deviceInfo *dto.DeviceDetectorInfo) *clickhouse.ActiveSession {
	activeSession := &clickhouse.ActiveSession{
		Id:             uuid.NewString(),
		Code:           encodeCode(email, deviceInfo),
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
		Lang:           deviceInfo.Lang,
	}

	result := database.ClickhouseInstance.Create(&activeSession)
	if result.Error != nil {
		return nil
	}

	return activeSession
}
