package queue

import "encoding/json"

type Task struct {
	Type     string          `json:"type"`
	Payload  json.RawMessage `json:"payload"`
	Provider string          `json:"provider"`
}

type IBANPayload struct {
	// Определите поля структуры для пакета данных IBAN.
}

type PayInPayload struct {
	// Определите поля структуры для пакета данных PayIn.
}

type PayOutPayload struct {
	// Определите поля структуры для пакета данных PayOut.
}

type EmailPayload struct {
	Id      int64       `json:"id"`
	Status  string      `json:"status"`
	Message string      `json:"message"`
	Data    interface{} `json:"data"`
}
