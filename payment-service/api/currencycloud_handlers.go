package api

import (
	"encoding/json"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"payment-service/providers"
	"payment-service/providers/currencycloud"
)

func CurrencyCloudAuth(c *fiber.Ctx, provider providers.PaymentProvider) error {
	// Получение данных формы из запроса
	authRequest := currencycloud.AuthRequest{LoginID: c.FormValue("login_id"), ApiKey: c.FormValue("api_key")}

	// Выполнение запроса на аутентификацию в CurrencyCloud API
	authResponse, err := provider.Auth(authRequest)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	// Формирование ответа с аутентификационным токеном
	response := currencycloud.AuthResponse{
		AuthToken: authResponse.(currencycloud.AuthResponse).AuthToken,
	}

	// Возвращение ответа в формате JSON
	return c.JSON(response)
}

// CurrencyCloudCheckStatus Реализация обработчика проверки статуса аккаунта
func CurrencyCloudCheckStatus(c *fiber.Ctx, provider providers.PaymentProvider) error {
	// Получаем данные запроса из JSON-тела
	var req currencycloud.StatusRequest
	if err := c.QueryParser(&req); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	// Вызываем метод проверки статуса аккаунта провайдера
	res, err := provider.Status(providers.StatusRequester(req))
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	// Отправляем ответ клиенту
	return c.JSON(res)
}

// CurrencyCloudIBAN Реализация обработчика добавления задачи в очередь для создания IBAN
func CurrencyCloudIBAN(c *fiber.Ctx, provider providers.PaymentProvider) error {
	var req currencycloud.IBANRequest
	if err := json.Unmarshal(c.Body(), &req); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	// Вызываем метод генерации IBAN провайдера
	res, err := provider.IBAN(providers.IBANRequester(req))
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(res)
}

// CurrencyCloudPayIn реализует обработчик добавления задачи в очередь для PayIn.
func CurrencyCloudPayIn(c *fiber.Ctx, provider providers.PaymentProvider) error {
	var req currencycloud.PayInRequest
	if err := json.Unmarshal(c.Body(), &req); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	// Вызываем метод генерации PayIn провайдера
	res, err := provider.PayIn(providers.PayInRequester(req))
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(res)
}

// CurrencyCloudPayOut реализует обработчик добавления задачи в очередь для PayOut.
func CurrencyCloudPayOut(c *fiber.Ctx, provider providers.PaymentProvider) error {
	var req currencycloud.PayOutRequest
	if err := json.Unmarshal(c.Body(), &req); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	// Вызываем метод генерации PayOut провайдера
	res, err := provider.PayOut(providers.PayOutRequester(req))
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(res)
}

// CurrencyCloudPayPostback Реализация обработчика postback-ов
func CurrencyCloudPayPostback(c *fiber.Ctx, provider providers.PaymentProvider) error {
	request := &currencycloud.PostbackRequest{}
	err := c.BodyParser(request)
	if err != nil {
		fmt.Printf("error: %v\n", err.Error())
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	responder, err := provider.PostBack(providers.PostBackRequester(request))
	if err != nil {
		fmt.Printf("error: %v\n", err.Error())
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(responder)
}
