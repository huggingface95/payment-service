package api

import (
	"github.com/gofiber/fiber/v2"
	"payment-service/api/handlers"
)

func SetupRoutes(app *fiber.App) {
	// Эндпоинт для проверки здоровья сервиса
	app.Get("/health", handlers.HealthCheck)

	api := app.Group("/api")
	api.Post("/auth", handlers.Auth)
	api.Post("/iban", handlers.IBAN)
	api.Post("/payin", handlers.PayIn)
	api.Post("/payout", handlers.PayOut)
	api.Get("/status/:transactionId", handlers.Status)
	api.Post("/postback", handlers.PostBack)
}
