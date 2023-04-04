package clickhouse

import (
	"time"
)

type ActiveSession struct {
	Id             string     `gorm:"column:id"`
	Provider       string     `gorm:"column:provider"`
	Email          string     `gorm:"column:email"`
	Ip             string     `gorm:"column:ip"`
	Country        string     `gorm:"column:country"`
	City           string     `gorm:"column:city"`
	Platform       string     `gorm:"column:platform"`
	Browser        string     `gorm:"column:browser"`
	BrowserVersion string     `gorm:"column:browser_version"`
	DeviceType     string     `gorm:"column:device_type"`
	Model          string     `gorm:"column:model"`
	Lang           string     `gorm:"column:lang"`
	Active         bool       `gorm:"column:active"`
	Trusted        bool       `gorm:"column:trusted"`
	Code           string     `gorm:"column:code"`
	CreatedAt      time.Time  `gorm:"column:created_at"`
	ExpiredAt      *time.Time `gorm:"column:expired_at"`
}

func (ActiveSession) TableName() string {
	return "active_sessions"
}
