package api

import (
	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/logger"
	"payment-service/providers"
	"payment-service/queue"
)

// Service - сервис для управления HTTP запросами
type Service struct {
	FiberClient *fiber.App
}

// NewService - создает новый экземпляр Service
func NewService(providersService *providers.Service, queueService *queue.Service) *Service {
	service := &Service{
		FiberClient: fiber.New(),
	}

	service.FiberClient.Use(logger.New())

	// Регистрируем маршруты API и передаем экземпляр Service
	SetupRoutes(service, providersService, queueService)

	return service
}
