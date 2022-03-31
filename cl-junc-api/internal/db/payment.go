package db

type Payment struct {
	Id            uint64  `bun:"id,pk,autoincrement"`
	Amount        float64 `bun:"amount_real,notnull"`
	PaymentNumber string  `bun:"payment_number,notnull"`
	Status        string  `bun:"status,notnull"`
}
