package api

import (
	"fmt"
	"github.com/gofiber/fiber/v2"
	"payment-service/db"
	"payment-service/providers"
	"payment-service/providers/clearjunction"
)

// ClearjunctionCheckStatus Реализация обработчика проверки статуса аккаунта
func ClearjunctionCheckStatus(c *fiber.Ctx, provider providers.PaymentProvider, services Services) error {
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

// ClearjunctionIBANPostback Реализация обработчика postback
func ClearjunctionIBANPostback(c *fiber.Ctx, provider *clearjunction.ClearJunction, services Services) error {
	request := &clearjunction.IbanPostbackRequest{}
	err := c.BodyParser(request)
	if err != nil {
		return fmt.Errorf("ошибка при разборе JSON: %w", err)
	}

	if request.Status == clearjunction.StatusAccepted {
		account := &db.Account{
			OrderReference: request.OrderReference,
			AccountNumber:  request.Iban,
			AccountStateID: db.AccountStateIDWaitingForApproval,
		}
		// Формируем map с полями для обновления
		updateFields := map[string]interface{}{
			"account_state_id": account.AccountStateID,
			"account_number":   account.AccountNumber,
		}
		if err := services.DB.Conn.UpdateAccount(account, "order_reference", updateFields); err != nil {
			// Возвращаем JSON-ответ с ошибкой
			return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
		}

		return c.JSON(clearjunction.IbanPostbackResponse{OrderReference: request.OrderReference})
	}

	return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": "Wrong Request"})
}

func ClearjunctionPayPostback(c *fiber.Ctx, provider providers.PaymentProvider, services Services) error {
	// Реализация обработчика ClearjunctionPayPostback
	return nil
}

func ClearjunctionIBANQueue(c *fiber.Ctx, provider providers.PaymentProvider, services Services) error {
	// Реализация обработчика ClearjunctionIBANQueue
	return nil
}

func ClearjunctionPayInQueue(c *fiber.Ctx, provider providers.PaymentProvider, services Services) error {
	// Реализация обработчика ClearjunctionPayInQueue
	return nil
}

func ClearjunctionPayOutQueue(c *fiber.Ctx, provider providers.PaymentProvider, services Services) error {
	// Реализация обработчика ClearjunctionPayOutQueue
	return nil
}
