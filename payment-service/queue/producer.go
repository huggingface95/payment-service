package queue

import (
	"encoding/json"
)

func PublishMessage(service *Service, task *Task) error {
	bytes, err := json.Marshal(task)
	if err != nil {
		return err
	}

	err = service.Client.RPush(ctx, service.Name, bytes).Err()
	if err != nil {
		return err
	}

	return nil
}
