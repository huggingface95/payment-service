package api

import (
	"encoding/json"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"payment-service/providers"
	"payment-service/providers/clearjunction"
	"payment-service/queue"
)

// ClearjunctionCheckStatus Реализация обработчика проверки статуса аккаунта
func ClearjunctionCheckStatus(c *fiber.Ctx, provider providers.PaymentProvider) error {
	// Получаем данные запроса из JSON-тела
	var request clearjunction.StatusRequest
	if err := c.QueryParser(&request); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": "Invalid request"})
	}

	// Вызываем метод проверки статуса аккаунта провайдера
	statusRequest := providers.StatusRequester(request)
	if _, err := provider.Status(statusRequest); err != nil {
		return c.Status(fiber.StatusNotFound).JSON(fiber.Map{"error": err.Error()})
	}

	// Отправляем ответ клиенту
	return c.JSON(fiber.Map{"status": "success"})
}

// ClearjunctionIBANPostback Реализация обработчика postback для IBAN
func ClearjunctionIBANPostback(c *fiber.Ctx, provider *clearjunction.ClearJunction) error {
	request := &clearjunction.IbanPostbackRequest{}
	err := c.BodyParser(request)
	if err != nil {
		return fmt.Errorf("ошибка при разборе JSON: %w", err)
	}

	responder, err := provider.PostBack(request)
	if err != nil {
		fmt.Printf("error: %v\n", err.Error())
		return c.Status(fiber.StatusNotFound).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(responder)
}

// ClearjunctionPayPostback Реализация обработчика postback для PayOut и PayIn
func ClearjunctionPayPostback(c *fiber.Ctx, provider *clearjunction.ClearJunction) error {
	request := &clearjunction.PayPostbackRequest{}
	err := c.BodyParser(request)
	if err != nil {
		return fmt.Errorf("ошибка при разборе JSON: %w", err)
	}

	responder, err := provider.PostBack(providers.PostBackRequester(request))
	if err != nil {
		fmt.Printf("error: %v\n", err.Error())
		return c.Status(fiber.StatusNotFound).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(responder)
}

// ClearjunctionIBANQueue Реализация обработчика добавления задачи в очередь для IBAN
func ClearjunctionIBANQueue(c *fiber.Ctx, provider providers.PaymentProvider) error {
	var payload queue.IBANPayload
	err := json.Unmarshal(c.Body(), &payload)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"error": "Invalid request payload",
		})
	}

	payload.PostbackURL = provider.(*clearjunction.ClearJunction).PublicURL + "clearjunction/iban/postback"

	task := queue.Task{
		Type:     "IBAN",
		Payload:  json.RawMessage(c.Body()),
		Provider: "clearjunction", // Идентификатор провайдера
	}

	err = queue.PublishMessage(provider.(*clearjunction.ClearJunction).Services.Queue, &task)
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"error": "Failed to publish message to queue",
		})
	}

	return c.JSON(fiber.Map{
		"message": "Task added to IBAN queue",
	})
}

// ClearjunctionPayInQueue реализует обработчик добавления задачи в очередь для PayIn.
func ClearjunctionPayInQueue(c *fiber.Ctx, provider providers.PaymentProvider) error {
	var payload queue.PayInPayload
	err := json.Unmarshal(c.Body(), &payload)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"error": "Invalid request payload",
		})
	}

	payload.PostbackURL = provider.(*clearjunction.ClearJunction).PublicURL + "clearjunction/iban/postback"

	task := queue.Task{
		Type:     "PayIn",
		Provider: "clearjunction",
		Payload:  json.RawMessage(c.Body()),
	}

	err = queue.PublishMessage(provider.(*clearjunction.ClearJunction).Services.Queue, &task)
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"error": "Failed to publish message to queue",
		})
	}

	return c.JSON(fiber.Map{
		"message": "Task added to PayIn queue",
	})
}

// ClearjunctionPayOutQueue реализует обработчик добавления задачи в очередь для PayIn.
func ClearjunctionPayOutQueue(c *fiber.Ctx, provider providers.PaymentProvider) error {
	var payload queue.PayOutPayload
	err := json.Unmarshal(c.Body(), &payload)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"error": "Invalid request payload",
		})
	}

	task := queue.Task{
		Type:     "PayOut",
		Provider: "clearjunction",
		Payload:  json.RawMessage(c.Body()),
	}

	err = queue.PublishMessage(provider.(*clearjunction.ClearJunction).Services.Queue, &task)
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"error": "Failed to publish message to queue",
		})
	}

	return c.JSON(fiber.Map{
		"message": "Task added to PayOut queue",
	})
}
