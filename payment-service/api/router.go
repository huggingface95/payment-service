package api

import (
	"github.com/gofiber/fiber/v2"
	"payment-service/providers/clearjunction"
)

func SetupRoutes(services Services) {
	// Эндпоинт для проверки здоровья сервиса
	services.API.FiberClient.Get("/health", HealthCheck)

	// Группа handler-ов провайдера clearjunction
	group := services.API.FiberClient.Group("/clearjunction")
	providerConfig := services.Providers.Config["clearjunction"].(map[string]interface{})
	// Создаем экземпляр провайдера ClearJunction
	provider := clearjunction.New(
		services.Providers,
		providerConfig["key"].(string), providerConfig["password"].(string), providerConfig["url"].(string),
	)
	group.Get("/iban-company/check", func(c *fiber.Ctx) error {
		return ClearjunctionCheckStatus(c, provider, services)
	})
	group.Post("/iban/postback", func(c *fiber.Ctx) error {
		return ClearjunctionIBANPostback(c, provider, services)
	})
	group.Post("/postback", func(c *fiber.Ctx) error {
		return ClearjunctionPayPostback(c, provider, services)
	})
	group.Post("/iban-queue", func(c *fiber.Ctx) error {
		return ClearjunctionIBANQueue(c, provider, services)
	})
	group.Post("/payin-queue", func(c *fiber.Ctx) error {
		return ClearjunctionPayInQueue(c, provider, services)
	})
	group.Post("/payout-queue", func(c *fiber.Ctx) error {
		return ClearjunctionPayOutQueue(c, provider, services)
	})
}
