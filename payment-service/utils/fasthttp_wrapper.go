package utils

import (
	"encoding/json"
	"fmt"
	"github.com/gofiber/fiber/v2"
	"github.com/valyala/fasthttp"
	"sync"
)

type FastHTTP struct {
	*fasthttp.Client
	ReqHeaders sync.Map
}

// Request - выполняет HTTP запрос с заданным методом, endpoint-ом и параметрами
func (c *FastHTTP) Request(method string, endpoint string, params interface{}, middleware func(requestBody []byte) error) ([]byte, error) {
	// Создание объекта запроса fasthttp
	req := fasthttp.AcquireRequest()
	defer fasthttp.ReleaseRequest(req)

	// Получение заголовков из sync.Map
	c.ReqHeaders.Range(func(key, value interface{}) bool {
		req.Header.Set(key.(string), value.(string))
		return true
	})

	req.Header.SetContentType("application/json")
	req.SetRequestURI(endpoint)

	// Создание объекта ответа fasthttp
	res := fasthttp.AcquireResponse()
	defer fasthttp.ReleaseResponse(res)

	// Преобразование параметров запроса в JSON
	paramsBytes, err := json.Marshal(params)
	if err != nil {
		return nil, fmt.Errorf("ошибка преобразования параметров запроса в JSON: %w", err)
	}

	switch method {
	case fiber.MethodPost:
		req.Header.SetMethod(fiber.MethodPost)

		req.SetBody(paramsBytes)

		if middleware != nil {
			if err := middleware(paramsBytes); err != nil {
				return nil, err
			}
		}
	case fiber.MethodGet:
		req.Header.SetMethod(fiber.MethodGet)

		// Добавление параметров запроса в виде query параметров
		paramsMap := map[string]interface{}{}
		if err := json.Unmarshal(paramsBytes, &paramsMap); err != nil {
			return nil, err
		}
		for key, value := range paramsMap {
			req.URI().QueryArgs().Add(key, fmt.Sprintf("%v", value))
		}

		if middleware != nil {
			if err := middleware(nil); err != nil {
				return nil, err
			}
		}
	default:
		return nil, fmt.Errorf("неподдерживаемый HTTP метод: %s", method)
	}

	req.Header.SetContentType(fiber.MIMEApplicationJSON)

	// Отправка запроса с использованием клиента fasthttp
	if err := c.Do(req, res); err != nil {
		return nil, fmt.Errorf("ошибка отправки GET запроса: %w", err)
	}

	// Проверка статусного кода ответа
	if res.StatusCode() >= fasthttp.StatusMultipleChoices {
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
