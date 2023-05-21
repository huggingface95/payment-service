package handlers

import (
	"encoding/json"
	"github.com/gofiber/fiber/v2"
	"payment-service/providers"
	"payment-service/providers/clearjunction"
	"payment-service/queue"
)

// ClearjunctionCheckStatus Реализация обработчика проверки статуса аккаунта
func ClearjunctionCheckStatus(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Получаем данные запроса из JSON-тела
	var request clearjunction.StatusRequest
	if err := c.QueryParser(&request); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": "Invalid request"})
	}

	// Вызываем метод генерации IBAN провайдера
	statusRequest := providers.StatusRequester(request)
	if _, err := provider.Status(statusRequest); err != nil {
		return c.Status(fiber.StatusNotFound).JSON(fiber.Map{"error": err.Error()})
	}

	// Отправляем ответ клиенту
	return c.JSON(fiber.Map{"status": "success"})
}

// ClearjunctionIBANPostback Реализация обработчика postback
func ClearjunctionIBANPostback(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Получаем тело из локального контекста
	bodyBytes, ok := c.Locals("body").([]byte)
	// Проверяем, есть ли тело запроса
	if !ok {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": "Empty request body"})
	}

	// Получаем данные запроса из JSON-тела
	var request clearjunction.PostBackRequest
	if err := json.Unmarshal(bodyBytes, &request); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": "Invalid request"})
	}

	// Создаем задачу для обработчика очереди
	task := queue.Task{
		Type:     "postback",
		Payload:  bodyBytes,
		Provider: c.Path(),
	}

	// Публикуем задачу в очередь
	err := queue.PublishMessage(queueService.Client, "postback_queue", &task)
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": "Failed to publish task to the queue"})
	}

	// Возвращаем успешный ответ
	return c.SendString("Task successfully added to the queue")
}

func ClearjunctionPayPostback(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Реализация обработчика ClearjunctionPayPostback
	return nil
}

func ClearjunctionIBANQueue(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Реализация обработчика ClearjunctionIBANQueue
	return nil
}

func ClearjunctionPayInQueue(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Реализация обработчика ClearjunctionPayInQueue
	return nil
}

func ClearjunctionPayOutQueue(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Реализация обработчика ClearjunctionPayOutQueue
	return nil
}
