package utils

import (
	"encoding/json"
	"fmt"
	"io"
	"net/http"
)

func HandleResponseError(response *http.Response) error {
	if response.StatusCode >= 400 {
		// Чтение тела ответа
		body, err := io.ReadAll(response.Body)
		if err != nil {
			return fmt.Errorf("failed to read response body: %w", err)
		}
		defer response.Body.Close()

		// Преобразование тела ответа в json.RawMessage
		rawMessage := json.RawMessage(body)

		// Попытка разбора JSON из тела ответа
		var errorResponse interface{}
		if json.Unmarshal(body, &errorResponse) == nil {
			// Если удалось разобрать JSON, создаем ошибку с текстом из JSON и кодом состояния
			return fmt.Errorf("response error (status %d): %v", response.StatusCode, errorResponse)
		}

		// Если не удалось разобрать JSON, создаем ошибку с текстом из тела ответа и кодом состояния
		return fmt.Errorf("response error (status %d): %s", response.StatusCode, string(rawMessage))
	}

	return nil
}
