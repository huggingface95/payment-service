package models

import "encoding/json"

type PaymentRequest struct {
	PaymentId uint64  `json:"paymentId"`
	Currency  string  `json:"currency"`
	Amount    float64 `json:"amount"`
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
