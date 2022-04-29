package db

import "github.com/uptrace/bun"

type Provider struct {
	bun.BaseModel `bun:"table:payment_provider"`

	Id   uint64 `bun:"id,pk,autoincrement"`
	Name string `bun:"name"`
}
