package api

import (
	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/logger"
	"github.com/spf13/viper"
)

// Service - сервис для управления HTTP запросами
type Service struct {
	FiberClient *fiber.App
	Config      map[string]interface{}
}

// NewService - создает новый экземпляр Service
func NewService(services Services) *Service {
	service := &Service{
		FiberClient: fiber.New(),
		Config:      viper.Get("server").(map[string]interface{}),
	}

	service.FiberClient.Use(logger.New())

	services.API = service

	// Регистрируем маршруты API и передаем экземпляр Service
	SetupRoutes(services)

	return service
}
