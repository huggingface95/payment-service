package clickhouse

import (
	"time"
)

type AuthenticationLog struct {
	Id             string    `gorm:"column:id"`
	Provider       string    `gorm:"column:provider"`
	Email          string    `gorm:"column:email"`
	Platform       string    `gorm:"column:platform"`
	Browser        string    `gorm:"column:browser"`
	BrowserVersion string    `gorm:"column:browser_version"`
	DeviceType     string    `gorm:"column:device_type"`
	Model          string    `gorm:"column:model"`
	Lang           string    `gorm:"column:lang"`
	Company        string    `gorm:"column:company"`
	Domain         string    `gorm:"column:domain"`
	Ip             string    `gorm:"column:ip"`
	Country        string    `gorm:"column:country"`
	City           string    `gorm:"column:city"`
	Status         string    `gorm:"column:status"`
	Info           string    `gorm:"column:info"`
	Code           string    `gorm:"column:code"`
	CreatedAt      time.Time `gorm:"column:created_at"`
	ExpiredAt      time.Time `gorm:"column:expired_at"`
}

func (AuthenticationLog) TableName() string {
	return "authentication_log"
}
