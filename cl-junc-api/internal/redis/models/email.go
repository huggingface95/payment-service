package models

import (
	"encoding/json"
)

type Email struct {
	Id      int64       `json:"id"`
	Status  string      `json:"status"`
	Message string      `json:"message"`
	Data    interface{} `json:"data"`
}

// MarshalBinary -
func (e *Email) MarshalBinary() ([]byte, error) {
	return json.Marshal(e)
}

// UnmarshalBinary -
func (e *Email) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &e); err != nil {
		return err
	}

	return nil
}
