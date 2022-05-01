package db

import "github.com/uptrace/bun"

type Account struct {
	bun.BaseModel `bun:"table:accounts"`

	Id               uint64  `bun:"id,pk,autoincrement"`
	ClientId         uint64  `bun:"client_id"`
	AvailableBalance float64 `bun:"available_balance"`
	CurrentBalance   float64 `bun:"current_balance"`
	ReservedBalance  float64 `bun:"reserved_balance"`
}
