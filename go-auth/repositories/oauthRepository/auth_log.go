package oauthRepository

import (
	"github.com/google/uuid"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/clickhouse"
	"time"
)

func HasActiveAuthLogWithConditions(activeSessionId string) (authLog *clickhouse.AuthenticationLog) {
	query := database.ClickhouseInstance.
		Order("created_at desc").
		Limit(1).
		Where("status = ?", "login").
		Where("active_session_id = ?", activeSessionId).
		Where("expired_at <= ?", time.Now().String())

	query.Find(&authLog)

	return authLog
}

func InsertAuthLog(status string, activeSessionId string, userTime time.Time) *clickhouse.AuthenticationLog {
	authLog := &clickhouse.AuthenticationLog{
		Id:              uuid.NewString(),
		ActiveSessionId: activeSessionId,
		Status:          status,
		ExpiredAt:       userTime,
	}

	result := database.ClickhouseInstance.Create(&authLog)

	if result.Error != nil {
		return nil
	}

	return authLog
}
