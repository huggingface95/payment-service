package queue

import (
	"encoding/json"
	"github.com/go-redis/redis/v8"
)

func StartConsumer(client *redis.Client, queueName string) error {
	for {
		msg, err := client.BLPop(ctx, 0, queueName).Result()
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
			HandleIBAN(task.Provider, &payload) // передайте task.Provider
		case "payin":
			var payload PayInPayload
			json.Unmarshal(task.Payload, &payload)
			HandlePayIn(task.Provider, &payload) // передайте task.Provider
		case "payout":
			var payload PayOutPayload
			json.Unmarshal(task.Payload, &payload)
			HandlePayOut(task.Provider, &payload) // передайте task.Provider
		default:
			// Неизвестный тип задачи, пропускаем
			continue
		}
	}
}
