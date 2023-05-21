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
	service.FiberClient.Get("/health", handlers.HealthCheck)

	// Группа handler-ов провайдера clearjunction
	group := service.FiberClient.Group("/clearjunction")
	providerConfig := providersService.Config["clearjunction"].(map[string]interface{})
	// Создаем экземпляр провайдера ClearJunction
	provider := clearjunction.NewClearJunction(
		providersService,
		providerConfig["key"].(string), providerConfig["password"].(string), providerConfig["url"].(string),
	)
	group.Get("/iban-company/check", func(c *fiber.Ctx) error {
		return handlers.ClearjunctionCheckStatus(c, provider, queueService)
	})
	group.Post("/iban/postback", func(c *fiber.Ctx) error {
		return handlers.ClearjunctionIBANPostback(c, provider, queueService)
	})
	group.Post("/postback", func(c *fiber.Ctx) error {
		return handlers.ClearjunctionPayPostback(c, provider, queueService)
	})
	group.Post("/iban-queue", func(c *fiber.Ctx) error {
		return handlers.ClearjunctionIBANQueue(c, provider, queueService)
	})
	group.Post("/payin-queue", func(c *fiber.Ctx) error {
		return handlers.ClearjunctionPayInQueue(c, provider, queueService)
	})
	group.Post("/payout-queue", func(c *fiber.Ctx) error {
		return handlers.ClearjunctionPayOutQueue(c, provider, queueService)
	})
}
