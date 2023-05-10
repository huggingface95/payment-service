package queue

import (
	"encoding/json"
	"github.com/go-redis/redis/v8"
)

func PublishMessage(client *redis.Client, queueName string, task *Task) error {
	bytes, err := json.Marshal(task)
	if err != nil {
		return err
	}

	err = client.RPush(ctx, queueName, bytes).Err()
	if err != nil {
		return err
	}

	return nil
}
