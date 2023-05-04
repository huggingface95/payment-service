package queue

import (
	"github.com/streadway/amqp"
)

func ConnectRabbitMQ(connectionString string) (*amqp.Connection, error) {
	conn, err := amqp.Dial(connectionString)
	if err != nil {
		return nil, err
	}
	return conn, nil
}
