package models

import "encoding/json"

type Email struct {
	Id      uint64 `json:"id"`
	Type    string `json:"type"`
	Status  string `json:"status"`
	Message string `json:"message"`
	Error   []struct {
		Code    string `json:"code"`
		Message string `json:"message"`
		Details string `json:"details"`
	} `json:"messages"`
	Data    interface{}       `json:"data"`
	Details map[string]string `json:"details"`
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
