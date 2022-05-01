package db

import "github.com/uptrace/bun"

type Transaction struct {
	bun.BaseModel `bun:"table:transactions"`

	Id          uint64  `bun:"id,pk,autoincrement"`
	PaymentId   uint64  `bun:"payment_id"`
	Amount      float64 `bun:"amount"`
	BalancePrev float64 `bun:"balance_prev"`
	BalanceNext float64 `bun:"balance_next"`
}
