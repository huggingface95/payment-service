package db

import "cl-junc-api/internal/clearjunction/models"

type Payment struct {
	Id            uint64  `bun:"id,pk,autoincrement"`
	Amount        float64 `bun:"amount_real,notnull"`
	PaymentNumber string  `bun:"payment_number,notnull"`
	Status        string  `bun:"status,notnull"`
}

func (p *Payment) ToPayInRequest() *models.PayInInvoiceRequest {
	return &models.PayInInvoiceRequest{
		PaymentType: "bank",
		PaymentId:   p.Id,
	}
}
