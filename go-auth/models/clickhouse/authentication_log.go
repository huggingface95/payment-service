package clickhouse

import (
	"time"
)

type AuthenticationLog struct {
	Id              string    `gorm:"column:id"`
	ActiveSessionId string    `gorm:"column:active_session_id"`
	Status          string    `gorm:"column:status"`
	CreatedAt       time.Time `gorm:"column:created_at"`
	ExpiredAt       time.Time `gorm:"column:expired_at"`
}

func (AuthenticationLog) TableName() string {
	return "authentication_log"
}
