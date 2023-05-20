package api

import (
	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/logger"
	"payment-service/providers"
	"payment-service/queue"
)

// Service - сервис для управления очередями
type Service struct {
	Client *fiber.App
}

// NewService - создает новый экземпляр Service
func NewService(providersService *providers.Service, queueService *queue.Service) *Service {
	service := &Service{
		Client: fiber.New(),
	}

	service.Client.Use(logger.New())

	// Регистрируем маршруты API и передаем экземпляр Service
	SetupRoutes(service, providersService, queueService)

	return service
}
