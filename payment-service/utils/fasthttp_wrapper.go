package utils

import (
	"encoding/json"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"github.com/valyala/fasthttp"
)

type FastHTTP struct {
	*fasthttp.Client
	ReqHeaders map[string]string
}

// Request - выполняет HTTP запрос с заданным методом, эндпоинтом и параметрами
func (c *FastHTTP) Request(method string, endpoint string, params map[string]interface{}) ([]byte, error) {
	var responseBody []byte

	// Создание объекта запроса fasthttp
	req := fasthttp.AcquireRequest()
	defer fasthttp.ReleaseRequest(req)

	for k, v := range c.ReqHeaders {
		req.Header.Set(k, v)
	}

	req.Header.SetContentType("application/json")
	req.SetRequestURI(endpoint)

	// Создание объекта ответа fasthttp
	res := fasthttp.AcquireResponse()
	defer fasthttp.ReleaseResponse(res)

	switch method {
	case fiber.MethodPost:
		// Преобразование параметров запроса в JSON
		body, err := json.Marshal(params)
		if err != nil {
			return nil, fmt.Errorf("ошибка преобразования параметров запроса в JSON: %w", err)
		}

		req.Header.SetMethod(fiber.MethodPost)
		req.SetBody(body)

		// Отправка POST запроса с использованием клиента fasthttp
		if err := c.Do(req, res); err != nil {
			return nil, fmt.Errorf("ошибка отправки POST запроса: %w", err)
		}

		// Проверка статусного кода ответа
		if res.StatusCode() != fasthttp.StatusOK {
			return nil, c.handleResponseError(res)
		}

		responseBody = res.Body()
	case fiber.MethodGet:
		req.Header.SetMethod(fiber.MethodGet)

		// Добавление параметров запроса в виде query параметров
		for key, value := range params {
			req.URI().QueryArgs().Add(key, fmt.Sprintf("%v", value))
		}

		// Отправка GET запроса с использованием клиента fasthttp
		if err := c.Do(req, res); err != nil {
			return nil, fmt.Errorf("ошибка отправки GET запроса: %w", err)
		}

		// Проверка статусного кода ответа
		if res.StatusCode() != fasthttp.StatusOK {
			return nil, c.handleResponseError(res)
		}

		responseBody = res.Body()
	default:
		return nil, fmt.Errorf("неподдерживаемый HTTP метод: %s", method)
	}

	return responseBody, nil
}

// handleResponseError - обрабатывает ошибку ответа
func (c *FastHTTP) handleResponseError(response *fasthttp.Response) error {
	if response.StatusCode() >= 400 {
		// Чтение тела ответа
		body := response.Body()

		// Преобразование тела ответа в json.RawMessage
		rawMessage := json.RawMessage(body)

		// Попытка разбора JSON из тела ответа
		var errorResponse interface{}
		if json.Unmarshal(body, &errorResponse) == nil {
			// Если удалось разобрать JSON, создаем ошибку с текстом из JSON и кодом состояния
			return fmt.Errorf("ошибка ответа (статус %d): %v", response.StatusCode(), errorResponse)
		}

		// Если не удалось разобрать JSON, создаем ошибку с текстом из тела ответа и кодом состояния
		return fmt.Errorf("ошибка ответа (статус %d): %s", response.StatusCode(), string(rawMessage))
	}

	return nil
}
