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

		provider, err := providersService.GetProvider(task.Provider)
		if err != nil {
			return err
		}

		switch task.Type {
		case "iban":
			var payload IBANPayload
			json.Unmarshal(task.Payload, &payload)
			HandleIBAN(provider, &payload)
		case "payin":
			var payload PayInPayload
			json.Unmarshal(task.Payload, &payload)
			HandlePayIn(provider, &payload)
		case "payout":
			var payload PayOutPayload
			json.Unmarshal(task.Payload, &payload)
			HandlePayOut(provider, &payload)
		case "postback":
			HandlePostBack(provider, &task.Payload)
		default:
			// Неизвестный тип задачи, пропускаем
			continue
		}
	}
}
