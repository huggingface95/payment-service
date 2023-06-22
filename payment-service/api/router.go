package api

import (
	"fmt"
	"github.com/gofiber/fiber/v2"
	"payment-service/providers"
	"payment-service/providers/clearjunction"
	"payment-service/providers/currencycloud"
)

func SetupRoutes(services Services) {
	// Эндпоинт для проверки здоровья сервиса
	services.API.FiberClient.Get("/health", HealthCheck)

	// Группа handler-ов провайдера ClearJunction
	{
		providerName := clearjunction.GetName()
		// Создание новой группы с именем, сформированным на основе переменной providerName
		group := services.API.FiberClient.Group("/" + providerName)
		// Создаем экземпляр провайдера ClearJunction
		provider, err := getProvider(services, providerName)
		if err != nil {
			panic(err)
		}
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
		providerName := currencycloud.GetName()
		// Создание новой группы с именем, сформированным на основе переменной providerName
		group := services.API.FiberClient.Group("/" + providerName)
		// Создаем экземпляр провайдера ClearJunction
		provider, err := getProvider(services, providerName)
		if err != nil {
			panic(err)
		}
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
		group.Post("/rates", func(c *fiber.Ctx) error {
			return CurrencyCloudRates(c, provider)
		})
		group.Post("/convert", func(c *fiber.Ctx) error {
			return CurrencyCloudConvert(c, provider)
		})
		group.Post("/postback", func(c *fiber.Ctx) error {
			return CurrencyCloudPostback(c, provider)
		})
	}
}

func getProvider(services Services, providerName string) (providers.PaymentProvider, error) {
	serverConfig := services.API.Config
	providerConfig := services.Providers.Config[providerName].(map[string]interface{})

	switch providerName {
	case "clearjunction":
		providerServices := clearjunction.Services{
			Providers: services.Providers,
			Queue:     services.Queue,
			DB:        services.DB,
		}
		provider := clearjunction.New(
			providerServices,
			providerConfig["key"].(string),
			providerConfig["password"].(string),
			providerConfig["base_url"].(string),
			serverConfig["public_url"].(string),
		)
		return provider, nil
	case "currencycloud":
		providerServices := currencycloud.Services{
			Providers: services.Providers,
			Queue:     services.Queue,
			DB:        services.DB,
		}
		provider := currencycloud.New(
			providerServices,
			providerConfig["login_id"].(string),
			providerConfig["api_key"].(string),
			providerConfig["base_url"].(string),
			serverConfig["public_url"].(string),
		)
		return provider, nil
	default:
		return nil, fmt.Errorf("unknown provider")
	}
}
