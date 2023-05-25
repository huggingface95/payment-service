package queue

import (
	"encoding/json"
	"payment-service/providers"
)

func StartConsumer(service *Service, providersService *providers.Service) error {
	for {
		msg, err := service.Client.BLPop(ctx, 0, service.Name).Result()
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
			var payload providers.IBANRequester
			json.Unmarshal(task.Payload, &payload)
			HandleIBAN(provider, payload)
		case "payin":
			var payload providers.PayInRequester
			json.Unmarshal(task.Payload, &payload)
			HandlePayIn(provider, payload)
		case "payout":
			var payload providers.PayOutRequester
			json.Unmarshal(task.Payload, &payload)
			HandlePayOut(provider, payload)
		default:
			// Неизвестный тип задачи, пропускаем
			continue
		}
	}
}
