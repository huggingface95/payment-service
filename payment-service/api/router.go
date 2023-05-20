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
		providersService,
		providerConfig["key"].(string), providerConfig["password"].(string), providerConfig["url"].(string),
	)
	group.Use(func(c *fiber.Ctx) error {
		// Читаем тело и преобразуем его в байтовый массив
		bodyBytes := c.Body()

		// Устанавливаем заголовки для авторизации с учётом тела запроса
		provider.SetAuthHeaders(bodyBytes)

		// Сохраняем тело в локальном контексте, чтобы можно было прочитать его снова
		c.Locals("body", bodyBytes)

		return c.Next()
	})
	group.Get("/iban-company/check", func(c *fiber.Ctx) error {
		return handlers.CheckStatus(c, provider, queueService)
	})
	group.Post("/iban/postback", func(c *fiber.Ctx) error {
		return handlers.IBANPostback(c, provider, queueService)
	})
	group.Post("/postback", func(c *fiber.Ctx) error {
		return handlers.PayPostback(c, provider, queueService)
	})
	group.Post("/iban-queue", func(c *fiber.Ctx) error {
		return handlers.IBANQueue(c, provider, queueService)
	})
	group.Post("/payin-queue", func(c *fiber.Ctx) error {
		return handlers.PayInQueue(c, provider, queueService)
	})
	group.Post("/payout-queue", func(c *fiber.Ctx) error {
		return handlers.PayOutQueue(c, provider, queueService)
	})
}
