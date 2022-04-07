package models

import "encoding/json"

type Payment struct {
	Amount    float64 `json:"amount"`
	Currency  string  `json:"currency"`
	PaymentId uint64  `json:"payment_id"`
}

// MarshalBinary -
func (p *Payment) MarshalBinary() ([]byte, error) {
	return json.Marshal(p)
}

// UnmarshalBinary -
func (p *Payment) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &p); err != nil {
		return err
	}

	return nil
}
