package models

import (
	"cl-junc-api/internal/clearjunction/models"
	"encoding/json"
)

type Email struct {
	Id      uint64                               `json:"id"`
	Type    string                               `json:"type"`
	Status  string                               `json:"status"`
	Message string                               `json:"message"`
	Error   []models.PayInPayoutResponseMessages `json:"messages"`
	Data    interface{}                          `json:"data"`
	Details map[string]string                    `json:"details"`
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
