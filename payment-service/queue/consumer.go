package queue

import (
	"encoding/json"
	"github.com/streadway/amqp"
)

func StartConsumer(conn *amqp.Connection, queueName string) error {
	channel, err := conn.Channel()
	if err != nil {
		return err
	}
	defer channel.Close()

	q, err := channel.QueueDeclare(
		queueName,
		true,
		false,
		false,
		false,
		nil,
	)
	if err != nil {
		return err
	}

	msgs, err := channel.Consume(
		q.Name,
		"",
		true,
		false,
		false,
		false,
		nil,
	)
	if err != nil {
		return err
	}

	for msg := range msgs {
		var task Task
		err := json.Unmarshal(msg.Body, &task)
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

	return nil
}
