package database

import (
	"fmt"
	"gorm.io/driver/clickhouse"
	"gorm.io/gorm"
	"jwt-authentication-golang/config"
	"log"
)

var ClickhouseInstance *gorm.DB
var ClickhouseDbError error

func ClickhouseConnect(config *config.ClickhouseConfig) {

	connectionString := fmt.Sprintf("clickhouse://%s:%s@%s:%d/%s?read_timeout=%s&write_timeout=%s", config.Login, config.Password, config.Host, config.Port, config.Database, config.TimeoutRead, config.TimeoutWrite)
	ClickhouseInstance, ClickhouseDbError = gorm.Open(clickhouse.Open(connectionString), &gorm.Config{})
	if ClickhouseDbError != nil {
		log.Fatal(ClickhouseDbError)
		panic(ClickhouseDbError)
	}
	log.Println("Connected to Database!")
}
