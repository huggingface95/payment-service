package db

import (
	"github.com/uptrace/bun"
)

type Status struct {
	bun.BaseModel `bun:"table:payment_status"`

	Id   uint64 `bun:"id,pk,autoincrement"`
	Name string `bun:"name"`
}
