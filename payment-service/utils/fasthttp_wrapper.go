package utils

import (
	"encoding/json"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"github.com/valyala/fasthttp"
)

type FastHTTP struct {
	*fasthttp.Client
	Headers map[string]string
}

// Request - выполняет HTTP запрос с заданным методом, эндпоинтом и параметрами
func (c *FastHTTP) Request(method string, endpoint string, params map[string]interface{}) ([]byte, error) {
	var responseBody []byte

	// Создание объекта запроса fasthttp
	req := fasthttp.AcquireRequest()
	defer fasthttp.ReleaseRequest(req)

	for k, v := range c.Headers {
		req.Header.Set(k, v)
	}

	req.Header.SetContentType("application/json")
	req.SetRequestURI(endpoint)

	switch method {
	case fiber.MethodPost:
		// Преобразование параметров запроса в JSON
		body, err := json.Marshal(params)
		if err != nil {
			return nil, fmt.Errorf("ошибка преобразования параметров запроса в JSON: %w", err)
		}

		req.Header.SetMethod(fiber.MethodPost)
		req.SetBody(body)

		// Создание объекта ответа fasthttp
		res := fasthttp.AcquireResponse()
		defer fasthttp.ReleaseResponse(res)

		// Отправка POST запроса с использованием клиента fasthttp
		if err := c.Do(req, res); err != nil {
			return nil, fmt.Errorf("ошибка отправки POST запроса: %w", err)
		}

		// Проверка статусного кода ответа
		if res.StatusCode() != fasthttp.StatusOK {
			return nil, fmt.Errorf("неожиданный статусный код: %d", res.StatusCode())
		}

		responseBody = res.Body()
	case fiber.MethodGet:
		req.Header.SetMethod(fiber.MethodGet)

		// Добавление параметров запроса в виде query параметров
		for key, value := range params {
			req.URI().QueryArgs().Add(key, fmt.Sprintf("%v", value))
		}

		// Создание объекта ответа fasthttp
		res := fasthttp.AcquireResponse()
		defer fasthttp.ReleaseResponse(res)

		// Отправка GET запроса с использованием клиента fasthttp
		if err := c.Do(req, res); err != nil {
			return nil, fmt.Errorf("ошибка отправки GET запроса: %w", err)
		}

		// Проверка статусного кода ответа
		if res.StatusCode() != fasthttp.StatusOK {
			return nil, fmt.Errorf("неожиданный статусный код: %d", res.StatusCode())
		}

		responseBody = res.Body()
	default:
		return nil, fmt.Errorf("неподдерживаемый HTTP метод: %s", method)
	}

	return responseBody, nil
}
