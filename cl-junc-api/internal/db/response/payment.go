package db

type PaymentResponse struct {
	Id     uint64  `bun:"id,pk,autoincrement"`
	Amount float64 `bun:"amount_real,notnull"`
	Status string  `bun:"status,notnull"`
}
