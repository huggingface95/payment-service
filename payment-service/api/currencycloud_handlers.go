package api

import (
	"encoding/json"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"payment-service/providers"
	"payment-service/providers/currencycloud"
)

// CurrencyCloudAuth - Реализация обработчика авторизации аккаунта
func CurrencyCloudAuth(c *fiber.Ctx, provider providers.PaymentProvider) error {
	// Создаем объект authRequest типа currencycloud.AuthRequest и инициализируем его значениями login_id и api_key
	authRequest := currencycloud.AuthRequest{LoginID: c.FormValue("login_id"), ApiKey: c.FormValue("api_key")}

	// Вызываем метод Auth объекта provider и передаем ему объект authRequest. Результат сохраняем в переменную authResponse
	authResponse, err := provider.Auth(authRequest)
	if err != nil {
		// Если произошла ошибка, возвращаем статус BadRequest и объект с ключом error и значением err.Error()
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	// Создаем объект response типа currencycloud.AuthResponse и инициализируем его значением поля AuthToken из объекта authResponse
	response := currencycloud.AuthResponse{
		AuthToken: authResponse.(currencycloud.AuthResponse).AuthToken,
	}

	// Возвращаем объект response в формате JSON
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

// CurrencyCloudPostback Реализация обработчика postback-ов
func CurrencyCloudPostback(c *fiber.Ctx, provider providers.PaymentProvider) error {
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

// CurrencyCloudRates реализует обработчик получения rate-ов.
func CurrencyCloudRates(c *fiber.Ctx, provider providers.PaymentProvider) error {
	req := &currencycloud.RatesRequest{}
	err := c.BodyParser(req)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	responder, err := provider.Custom(providers.CustomRequester(req))
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(responder)
}

// CurrencyCloudConvert реализует обработчик конвертации валют.
func CurrencyCloudConvert(c *fiber.Ctx, provider providers.PaymentProvider) error {
	req := &currencycloud.ConvertRequest{}
	err := c.BodyParser(req)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	responder, err := provider.Custom(providers.CustomRequester(req))
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(responder)
}
