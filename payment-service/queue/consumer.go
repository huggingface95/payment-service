package queue

import (
	"encoding/json"
	"payment-service/providers"
)

func StartConsumer(service *Service, providersService *providers.Service, queueName string) error {
	for {
		msg, err := service.Client.BLPop(ctx, 0, queueName).Result()
		if err != nil {
			return err
		}

		var task Task
		err = json.Unmarshal([]byte(msg[1]), &task)
		if err != nil {
			continue
		}

		switch task.Type {
		case "iban":
			var payload IBANPayload
			json.Unmarshal(task.Payload, &payload)
			HandleIBAN(providersService, &payload)
		case "payin":
			var payload PayInPayload
			json.Unmarshal(task.Payload, &payload)
			HandlePayIn(providersService, &payload)
		case "payout":
			var payload PayOutPayload
			json.Unmarshal(task.Payload, &payload)
			HandlePayOut(providersService, &payload)
		case "postback":
			HandlePostBack(providersService, &task.Payload)
		default:
			// Неизвестный тип задачи, пропускаем
			continue
		}
	}
}
