package db

import "github.com/uptrace/bun"

type Type struct {
	bun.BaseModel `bun:"table:payment_types"`

	Id   uint64 `bun:"id,pk,autoincrement"`
	Name string `bun:"name"`
}
