package db

import (
	"github.com/spf13/viper"
)

// Service - сервис для управления БД
type Service struct {
	Pg *Pg
}

// NewService - создает новый экземпляр Service
func NewService() *Service {
	service := &Service{}

	// Инициализация базы данных PostgreSQL
	newDB, err := NewDB(viper.GetString("db.connection_string"))
	if err != nil {
		panic(err)
	}

	service.Pg = newDB

	return service
}
