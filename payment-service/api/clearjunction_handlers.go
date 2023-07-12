package api

import (
	"encoding/json"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"payment-service/providers"
	"payment-service/providers/clearjunction"
	"payment-service/queue"
)

// ClearJunctionStatus Реализация обработчика проверки статуса генерации IBAN
func ClearJunctionStatus(c *fiber.Ctx, provider providers.PaymentProvider) error {
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

// ClearJunctionPostback Реализация обработчика postback
func ClearJunctionPostback(c *fiber.Ctx, provider providers.PaymentProvider) error {
	postbackRequest := providers.PostBackRequest{}
	if err := c.BodyParser(&postbackRequest); err != nil {
		fmt.Printf("postbackRequest parse error: %v\n", err.Error())
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	request := map[providers.PostbackTypeEnum]providers.PostBackRequester{
		providers.PostbackTypeIBAN:   &clearjunction.IBANPostbackRequest{},
		providers.PostbackTypePayIn:  &clearjunction.PayInPostbackRequest{},
		providers.PostbackTypePayOut: &clearjunction.PayOutPostbackRequest{},
	}[postbackRequest.Type]

	if err := c.BodyParser(request); err != nil {
		fmt.Printf("postback request parse error: %v\n", err.Error())
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

// ClearJunctionPayOutQueue реализует обработчик добавления задачи в очередь для PayOut.
func ClearJunctionPayOutQueue(c *fiber.Ctx, provider providers.PaymentProvider) (err error) {
	var req queue.PayOutPayload
	if err = json.Unmarshal(c.Body(), &req); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": err.Error()})
	}

	task := queue.Task{
		Type:     "PayOut",
		Provider: "clearjunction",
	}
	if task.Payload, err = json.Marshal(req); err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	if err := queue.PublishMessage(provider.(*clearjunction.ClearJunction).Services.Queue, &task); err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	return c.JSON(fiber.Map{"message": "Task added to PayOut queue"})
}
