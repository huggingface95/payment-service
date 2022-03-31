package models

type Payment struct {
	ClientOrder string `json:"clientOrder"`
}

type PaymentCommon interface {
}