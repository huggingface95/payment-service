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

// Request - выполняет HTTP запрос с заданным методом, endpoint-ом и параметрами
func (c *FastHTTP) Request(method string, endpoint string, params map[string]interface{}, middleware func(requestBody []byte)) ([]byte, error) {
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
		req.Header.SetMethod(fiber.MethodPost)

		// Преобразование параметров запроса в JSON
		body, err := json.Marshal(params)
		if err != nil {
			return nil, fmt.Errorf("ошибка преобразования параметров запроса в JSON: %w", err)
		}

		req.SetBody(body)

		middleware(body)
	case fiber.MethodGet:
		req.Header.SetMethod(fiber.MethodGet)

		// Добавление параметров запроса в виде query параметров
		for key, value := range params {
			req.URI().QueryArgs().Add(key, fmt.Sprintf("%v", value))
		}

		middleware(nil)
	default:
		return nil, fmt.Errorf("неподдерживаемый HTTP метод: %s", method)
	}

	req.Header.SetContentType(fiber.MIMEApplicationJSON)

	for k, v := range c.ReqHeaders {
		req.Header.Set(k, v)
	}

	// Отправка запроса с использованием клиента fasthttp
	if err := c.Do(req, res); err != nil {
		return nil, fmt.Errorf("ошибка отправки GET запроса: %w", err)
	}

	// Проверка статусного кода ответа
	if res.StatusCode() != fasthttp.StatusOK {
		return nil, c.handleResponseError(res)
	}

	return res.Body(), nil
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
