package db

import "github.com/uptrace/bun"

const CLEARJUNCTION = "ClearJunction"

type Provider struct {
	bun.BaseModel `bun:"table:payment_provider"`

	Id   ProviderDb `bun:"id,pk,autoincrement"`
	Name string     `bun:"name"`
}

type ProviderDb uint64
