package api

import (
	"github.com/gofiber/fiber/v2"
	"payment-service/providers/clearjunction"
)

func SetupRoutes(services Services) {
	// Эндпоинт для проверки здоровья сервиса
	services.API.FiberClient.Get("/health", HealthCheck)

	{ // Группа handler-ов провайдера clearjunction
		group := services.API.FiberClient.Group("/clearjunction")
		providerConfig := services.Providers.Config["clearjunction"].(map[string]interface{})

		// Создаем экземпляр структуры Services провайдера, передавая необходимые сервисы
		providerServices := clearjunction.Services{
			Providers: services.Providers,
			Queue:     services.Queue,
			DB:        services.DB,
		}

		// Создаем экземпляр провайдера ClearJunction
		provider := clearjunction.New(
			providerServices,
			providerConfig["key"].(string), providerConfig["password"].(string), providerConfig["url"].(string),
		)
		group.Get("/iban-company/check", func(c *fiber.Ctx) error {
			return ClearjunctionCheckStatus(c, provider)
		})
		group.Post("/iban/postback", func(c *fiber.Ctx) error {
			return ClearjunctionIBANPostback(c, provider)
		})
		group.Post("/postback", func(c *fiber.Ctx) error {
			return ClearjunctionPayPostback(c, provider)
		})
		group.Post("/iban-queue", func(c *fiber.Ctx) error {
			return ClearjunctionIBANQueue(c, provider)
		})
		group.Post("/payin-queue", func(c *fiber.Ctx) error {
			return ClearjunctionPayInQueue(c, provider)
		})
		group.Post("/payout-queue", func(c *fiber.Ctx) error {
			return ClearjunctionPayOutQueue(c, provider)
		})
	}
}
