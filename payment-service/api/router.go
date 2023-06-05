package api

import (
	"github.com/gofiber/fiber/v2"
	"payment-service/providers/clearjunction"
	"payment-service/providers/currencycloud"
)

func SetupRoutes(services Services) {
	// Эндпоинт для проверки здоровья сервиса
	services.API.FiberClient.Get("/health", HealthCheck)

	serverConfig := services.API.Config

	// Группа handler-ов провайдера ClearJunction
	{
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
			providerConfig["key"].(string),
			providerConfig["password"].(string),
			providerConfig["base_url"].(string),
			serverConfig["public_url"].(string),
		)
		group.Get("/iban-company/check", func(c *fiber.Ctx) error {
			return ClearJunctionCheckStatus(c, provider)
		})
		group.Post("/iban/postback", func(c *fiber.Ctx) error {
			return ClearJunctionIBANPostback(c, provider)
		})
		group.Post("/postback", func(c *fiber.Ctx) error {
			return ClearJunctionPayPostback(c, provider)
		})
		group.Post("/iban-queue", func(c *fiber.Ctx) error {
			return ClearJunctionIBANQueue(c, provider)
		})
		group.Post("/payin-queue", func(c *fiber.Ctx) error {
			return ClearJunctionPayInQueue(c, provider)
		})
		group.Post("/payout-queue", func(c *fiber.Ctx) error {
			return ClearJunctionPayOutQueue(c, provider)
		})
	}

	// Группа handler-ов провайдера CurrencyCloud
	{
		group := services.API.FiberClient.Group("/currencycloud")
		providerConfig := services.Providers.Config["currencycloud"].(map[string]interface{})

		// Создаем экземпляр структуры Services провайдера, передавая необходимые сервисы
		providerServices := currencycloud.Services{
			Providers: services.Providers,
			Queue:     services.Queue,
			DB:        services.DB,
		}

		// Создаем экземпляр провайдера CurrencyCloud
		provider := currencycloud.New(
			providerServices,
			providerConfig["login_id"].(string),
			providerConfig["api_key"].(string),
			providerConfig["base_url"].(string),
			serverConfig["public_url"].(string),
		)

		// Группа handler-ов провайдера CurrencyCloud
		group.Post("/auth", func(c *fiber.Ctx) error {
			return CurrencyCloudAuth(c, provider)
		})
		group.Get("/status", func(c *fiber.Ctx) error {
			return CurrencyCloudCheckStatus(c, provider)
		})
		group.Post("/iban", func(c *fiber.Ctx) error {
			return CurrencyCloudIBAN(c, provider)
		})
		group.Post("/payin", func(c *fiber.Ctx) error {
			return CurrencyCloudPayIn(c, provider)
		})
		group.Post("/payout", func(c *fiber.Ctx) error {
			return CurrencyCloudPayOut(c, provider)
		})
		//group.Post("/transfer", func(c *fiber.Ctx) error {
		//	return CurrencyCloudTransferBalance(c, provider)
		//})
		//group.Get("/rates", func(c *fiber.Ctx) error {
		//	return CurrencyCloudGetCurrencyRates(c, provider)
		//})
		//group.Post("/convert", func(c *fiber.Ctx) error {
		//	return CurrencyCloudConvertCurrency(c, provider)
		//})
		group.Post("/postback", func(c *fiber.Ctx) error {
			return CurrencyCloudPayPostback(c, provider)
		})
	}
}
