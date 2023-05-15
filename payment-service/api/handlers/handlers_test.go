package handlers

import (
	"bytes"
	"encoding/json"
	"net/http"
	"net/http/httptest"
	"testing"

	"github.com/gofiber/fiber/v2"
	"github.com/stretchr/testify/assert"

	"payment-service/providers/clearjunction"
)

func TestIBANHandler(t *testing.T) {
	// Создаем экземпляр Fiber приложения
	app := fiber.New()

	// Создаём тестовый обработчик события
	app.Post("/api/iban", func(c *fiber.Ctx) error {
		return c.SendString("Hello, World!")
	})

	// Создаем тестовый запрос с JSON-телом
	requestBody := map[string]interface{}{
		"clientCustomerId": "333",
	}
	var requestBodyBuffer bytes.Buffer
	assert.NoError(t, json.NewEncoder(&requestBodyBuffer).Encode(requestBody))
	req := httptest.NewRequest(http.MethodPost, "/api/iban", &requestBodyBuffer)
	req.Header.Set("Content-Type", "application/json")

	// Устанавливаем данные в контекст, аналогично коду в обработчике
	providersConfig := map[string]interface{}{
		"providers": map[string]interface{}{
			"clearjunction": map[string]interface{}{
				"url":      "https://sandbox.clearjunction.com/",
				"wallet":   "94ab8a20-650c-486f-955b-3a68258a589e",
				"key":      "94ab8bc4-4ed9-4468-9b28-c1979f418f3f",
				"password": "56pr6hwgqdss",
			},
		},
	}
	app.Use(func(c *fiber.Ctx) error {
		c.Context().SetUserValue("providers", providersConfig)
		return c.Next()
	})

	// Тестируем обработчик IBAN
	resp, _ := app.Test(req)

	// Проверяем ошибки и статус ответа
	assert.Equal(t, http.StatusOK, resp.StatusCode)

	// Проверяем ожидаемый JSON-ответ
	var response clearjunction.IBANResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	assert.NoError(t, err)

	// Дополнительные проверки, основанные на ожидаемых значениях

	// Пример: Проверяем наличие сгенерированных IBAN в ответе
	assert.NotEmpty(t, response.Ibans)
}
