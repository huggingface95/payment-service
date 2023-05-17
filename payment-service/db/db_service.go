package db

import (
	"github.com/spf13/viper"
)

// Service - сервис для управления БД
type Service struct {
	Conn *DB
}

// NewService - создает новый экземпляр Service
func NewService() *Service {
	service := &Service{}

	// Инициализация базы данных PostgreSQL
	dbConn, err := NewDB(viper.GetString("db.connection_string"))
	if err != nil {
		panic(err)
	}
	defer dbConn.Close()

	service.Conn = dbConn

	return service
}
