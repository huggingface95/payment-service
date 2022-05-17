package models

import (
	"encoding/json"
)

type PaymentRequest struct {
	Amount    float64 `json:"amount"`
	Currency  string  `json:"currency"`
	PaymentId uint64  `json:"payment_id"`
}

// MarshalBinary -
func (p *PaymentRequest) MarshalBinary() ([]byte, error) {
	return json.Marshal(p)
}

// UnmarshalBinary -
func (p *PaymentRequest) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &p); err != nil {
		return err
	}

	return nil
}
