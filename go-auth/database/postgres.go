package database

import (
	"fmt"
	"gorm.io/driver/postgres"
	"gorm.io/gorm"
	"jwt-authentication-golang/config"
	"log"
)

var PostgresInstance *gorm.DB
var PostgresError error

func PostgresConnect(config *config.PostgresConfig) {
	connectionString := fmt.Sprintf("host=%s user=%s password=%s dbname=%s port=%d sslmode=%s TimeZone=%s",
		config.Host, config.Login, config.Password, config.Database, config.Port, config.SslMode, config.TimeZone)

	PostgresInstance, PostgresError = gorm.Open(postgres.Open(connectionString), &gorm.Config{})
	if PostgresError != nil {
		log.Fatal(PostgresError)
		panic("Cannot connect to DB")
	}
	log.Println("Connected to Database!")
}
