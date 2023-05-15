package handlers

import (
	"github.com/gofiber/fiber/v2"
	"payment-service/providers"
	"payment-service/providers/clearjunction"
)

func HealthCheck(c *fiber.Ctx) error {
	return c.JSON(fiber.Map{"status": "OK"})
}

func Auth(c *fiber.Ctx, providerService *providers.ProviderService) error {
	// Реализация обработчика авторизации
	return nil
}

// IBAN Реализация обработчика генерации IBAN
func IBAN(c *fiber.Ctx, providerService *providers.ProviderService) error {
	// Получаем данные запроса из JSON-тела
	var request clearjunction.IBANRequest
	if err := c.BodyParser(&request); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": "Invalid request"})
	}

	// Вызываем метод генерации IBAN провайдера
	ibanRequest := providers.IBANRequester(request)
	ibanResponse, err := providerService.IBAN(ibanRequest)
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": "Failed to generate IBAN"})
	}

	// Формируем ответ со сгенерированными IBAN
	response := clearjunction.IBANResponse{
		Ibans: ibanResponse.GetIBANs(),
	}

	// Отправляем ответ клиенту
	return c.JSON(response)
}

func PayIn(c *fiber.Ctx, providerService *providers.ProviderService) error {
	// Реализация обработчика PayIn
	return nil
}

func PayOut(c *fiber.Ctx, providerService *providers.ProviderService) error {
	// Реализация обработчика PayOut
	return nil
}

func Status(c *fiber.Ctx, providerService *providers.ProviderService) error {
	// Реализация обработчика запроса статуса транзакции
	return nil
}

func PostBack(c *fiber.Ctx, providerService *providers.ProviderService) error {
	// Реализация обработчика PostBack
	return nil
}
