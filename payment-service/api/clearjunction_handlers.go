package api

import (
	"encoding/json"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"payment-service/providers"
	"payment-service/providers/clearjunction"
	"payment-service/queue"
)

// ClearJunctionCheckStatus Реализация обработчика проверки статуса аккаунта
func ClearJunctionCheckStatus(c *fiber.Ctx, provider providers.PaymentProvider) error {
	// Получаем данные запроса из JSON-тела
	var request clearjunction.StatusRequest
	if err := c.QueryParser(&request); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	// Вызываем метод проверки статуса аккаунта провайдера
	statusRequest := providers.StatusRequester(request)
	if _, err := provider.Status(statusRequest); err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	// Отправляем ответ клиенту
	return c.JSON(fiber.Map{"status": "success"})
}

// ClearJunctionIBANPostback Реализация обработчика postback для создания IBAN
func ClearJunctionIBANPostback(c *fiber.Ctx, provider providers.PaymentProvider) error {
	request := &clearjunction.IbanPostbackRequest{}
	err := c.BodyParser(request)
	if err != nil {
		fmt.Printf("error: %v\n", err.Error())
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	responder, err := provider.PostBack(request)
	if err != nil {
		fmt.Printf("error: %v\n", err.Error())
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(responder)
}

// ClearJunctionPayPostback Реализация обработчика postback для PayOut и PayIn
func ClearJunctionPayPostback(c *fiber.Ctx, provider providers.PaymentProvider) error {
	request := &clearjunction.PostbackRequest{}
	err := c.BodyParser(request)
	if err != nil {
		fmt.Printf("error: %v\n", err.Error())
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	responder, err := provider.PostBack(request)
	if err != nil {
		fmt.Printf("error: %v\n", err.Error())
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(responder)
}

// ClearJunctionIBANQueue Реализация обработчика добавления задачи в очередь для создания IBAN
func ClearJunctionIBANQueue(c *fiber.Ctx, provider providers.PaymentProvider) (err error) {
	currentProvider := provider.(*clearjunction.ClearJunction)

	var req queue.IBANPayload
	if err = json.Unmarshal(c.Body(), &req); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	if err := currentProvider.Services.Providers.Validator.Struct(req); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	task := queue.Task{
		Type:     "IBAN",
		Provider: "clearjunction", // Идентификатор провайдера
	}
	if task.Payload, err = json.Marshal(req); err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	if err := queue.PublishMessage(currentProvider.Services.Queue, &task); err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(fiber.Map{"message": "Task added to IBAN queue"})
}

// ClearJunctionPayInQueue реализует обработчик добавления задачи в очередь для PayIn.
func ClearJunctionPayInQueue(c *fiber.Ctx, provider providers.PaymentProvider) error {
	var request clearjunction.PayInRequest
	if err := json.Unmarshal(c.Body(), &request); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	request.PostbackURL = provider.(*clearjunction.ClearJunction).PublicURL + "clearjunction/postback"

	task := queue.Task{
		Type:     "PayIn",
		Provider: "clearjunction",
		Payload:  json.RawMessage(c.Body()),
	}

	if err := queue.PublishMessage(provider.(*clearjunction.ClearJunction).Services.Queue, &task); err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(fiber.Map{"message": "Task added to PayIn queue"})
}

// ClearJunctionPayOutQueue реализует обработчик добавления задачи в очередь для PayOut.
func ClearJunctionPayOutQueue(c *fiber.Ctx, provider providers.PaymentProvider) error {
	var request clearjunction.PayOutRequest
	if err := json.Unmarshal(c.Body(), &request); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	request.PostbackURL = provider.(*clearjunction.ClearJunction).PublicURL + "clearjunction/postback"

	task := queue.Task{
		Type:     "PayOut",
		Provider: "clearjunction",
		Payload:  json.RawMessage(c.Body()),
	}

	if err := queue.PublishMessage(provider.(*clearjunction.ClearJunction).Services.Queue, &task); err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(fiber.Map{"message": "Task added to PayOut queue"})
}
