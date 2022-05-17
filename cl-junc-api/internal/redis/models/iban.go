package models

import (
	"encoding/json"
)

type IbanRequest struct {
	AccountId uint64 `json:"id"`
}

// MarshalBinary -
func (i *IbanRequest) MarshalBinary() ([]byte, error) {
	return json.Marshal(i)
}

// UnmarshalBinary -
func (i *IbanRequest) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &i); err != nil {
		return err
	}

	return nil
}
