package api

import (
	"github.com/gofiber/fiber/v2"
	"payment-service/api/handlers"
	"payment-service/providers"
)

func SetupRoutes(app *fiber.App, providerService *providers.ProviderService) {
	// Эндпоинт для проверки здоровья сервиса
	app.Get("/health", handlers.HealthCheck)

	api := app.Group("/api")
	api.Post("/auth", func(c *fiber.Ctx) error { return handlers.Auth(c, providerService) })
	api.Post("/iban", func(c *fiber.Ctx) error { return handlers.IBAN(c, providerService) })
	api.Post("/payin", func(c *fiber.Ctx) error { return handlers.PayIn(c, providerService) })
	api.Post("/payout", func(c *fiber.Ctx) error { return handlers.PayOut(c, providerService) })
	api.Get("/status/:transactionId", func(c *fiber.Ctx) error { return handlers.Status(c, providerService) })
	api.Post("/postback", func(c *fiber.Ctx) error { return handlers.PostBack(c, providerService) })
}
