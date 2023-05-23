package api

import (
	"encoding/json"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"payment-service/db"
	"payment-service/providers"
	"payment-service/providers/clearjunction"
	"payment-service/queue"
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
		// Формируем map с полями для обновления и условиями where и передаём в функцию обновления таблицы
		if err := services.DB.Pg.Update("accounts",
			map[string]interface{}{"account_state_id": db.AccountStateWaitingForApproval, "account_number": request.Iban},
			map[string]interface{}{"order_reference": request.OrderReference},
		); err != nil {
			// Возвращаем JSON-ответ с ошибкой
			return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
		}

		return c.JSON(clearjunction.IbanPostbackResponse{OrderReference: request.OrderReference})
	}

	return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": "Wrong Request"})
}

func ClearjunctionPayPostback(c *fiber.Ctx, provider clearjunction.PaymentProvider, services Services) error {
	request := &clearjunction.PayPostbackRequest{}
	err := c.BodyParser(request)
	if err != nil {
		return fmt.Errorf("ошибка при разборе JSON: %w", err)
	}

	payment, err := services.DB.Pg.GetPaymentWithRelations(
		[]string{"Status", "Provider", "OperationType"},
		map[string]interface{}{"payment_number": request.OrderReference},
	)
	if err != nil {
		// Возвращаем JSON-ответ с ошибкой
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
	}

	if request.Status != payment.Status.Name {
		if err := services.DB.Pg.Update("payment",
			map[string]interface{}{"amount": request.Amount, "status_id": db.GetStatus(request.Status)},
			map[string]interface{}{"payment_number": request.OrderReference},
		); err != nil {
			// Возвращаем JSON-ответ с ошибкой
			return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": err.Error()})
		}

		if request.Status == "completed" {
			result, err := provider.PayoutApprove(request.OrderReference)
			if err != nil {
				return err
			}

			if len(result.Messages) > 0 {
				return nil
			}

			var nextBalance = float64(0)
			if payment.OperationTypeId == db.OperationTypeIncoming {
				nextBalance = payment.Account.CurrentBalance + request.Amount
			} else {
				nextBalance = payment.Account.CurrentBalance - request.Amount
			}

			if _, err = services.DB.Pg.Insert("transaction", map[string]interface{}{
				"payment_id":   payment.ID,
				"amount":       request.Amount,
				"balance_prev": payment.Account.CurrentBalance,
				"balance_next": nextBalance,
			}); err != nil {
				return err
			}

			if err := services.DB.Pg.Update("account",
				map[string]interface{}{"current_balance": nextBalance},
				map[string]interface{}{"id": payment.Account.ID},
			); err != nil {
				return err
			}
		}

		payload, err := json.Marshal(queue.EmailPayload{
			Id:      int64(payment.ID),
			Status:  request.Status,
			Message: "Payment postback status",
			Data:    request,
		})
		if err != nil {
			return err
		}
		if err := queue.PublishMessage(services.Queue, &queue.Task{
			Type:     "email",
			Payload:  payload,
			Provider: "clearjunction",
		}); err != nil {
			return err
		}

		return c.JSON(clearjunction.PayPostbackResponse{OrderReference: request.OrderReference})
	}

	return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"error": "Wrong Request"})
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
