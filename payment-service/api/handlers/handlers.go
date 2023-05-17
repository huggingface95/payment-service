package handlers

import (
	"encoding/json"
	"github.com/gofiber/fiber/v2"
	"payment-service/providers"
	"payment-service/providers/clearjunction"
	"payment-service/queue"
)

func HealthCheck(c *fiber.Ctx) error {
	return c.JSON(fiber.Map{"status": "OK"})
}

func Auth(c *fiber.Ctx, provider providers.PaymentProvider) error {
	// Реализация обработчика авторизации
	return nil
}

// IBAN Реализация обработчика проверки IBAN
func IBAN(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Получаем данные запроса из JSON-тела
	var request clearjunction.IBANRequest
	if err := c.QueryParser(&request); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": "Invalid request"})
	}

	// Вызываем метод генерации IBAN провайдера
	ibanRequest := providers.IBANRequester(request)
	ibanResponse, err := provider.IBAN(ibanRequest)
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

func PayIn(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Реализация обработчика PayIn
	return nil
}

func PayOut(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Реализация обработчика PayOut
	return nil
}

func Status(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Реализация обработчика запроса статуса транзакции
	return nil
}

// PostBack Реализация обработчика PostBack
func PostBack(c *fiber.Ctx, provider providers.PaymentProvider, queueService *queue.Service) error {
	// Читаем тело и преобразуем его в байтовый массив
	body := c.Body()

	// Получаем данные запроса из JSON-тела
	var request clearjunction.PostBackRequest
	if err := json.Unmarshal(body, &request); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"error": "Invalid request"})
	}

	// Создаем задачу для обработчика очереди
	task := queue.Task{
		Type:     "postback",
		Payload:  body,
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
