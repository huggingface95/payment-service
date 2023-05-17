package api

import (
	"github.com/gofiber/fiber/v2"
	"payment-service/api/handlers"
	"payment-service/providers"
	"payment-service/providers/clearjunction"
	"payment-service/queue"
)

func SetupRoutes(service *Service, providersService *providers.Service, queueService *queue.Service) {
	// Эндпоинт для проверки здоровья сервиса
	service.Client.Get("/health", handlers.HealthCheck)

	// Группа handler-ов провайдера clearjunction
	group := service.Client.Group("/clearjunction")
	providerConfig := providersService.Config["clearjunction"].(map[string]interface{})
	// Создаем экземпляр провайдера ClearJunction
	provider := clearjunction.NewClearJunction(
		providerConfig["key"].(string), providerConfig["password"].(string), providerConfig["url"].(string),
	)
	group.Get("/iban-company/check", func(c *fiber.Ctx) error {
		return handlers.IBAN(c, provider, queueService)
	})
	group.Post("/postback", func(c *fiber.Ctx) error {
		return handlers.PostBack(c, provider, queueService)
	})
	group.Post("/payin", func(c *fiber.Ctx) error {
		return handlers.PayIn(c, provider, queueService)
	})
	group.Post("/payout", func(c *fiber.Ctx) error {
		return handlers.PayOut(c, provider, queueService)
	})
	group.Get("/status/:transactionId", func(c *fiber.Ctx) error {
		return handlers.Status(c, provider, queueService)
	})
	group.Post("/postback", func(c *fiber.Ctx) error {
		return handlers.PostBack(c, provider, queueService)
	})
}
