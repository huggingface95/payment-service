package db

import "github.com/uptrace/bun"

type Account struct {
	bun.BaseModel `bun:"table:accounts"`

	Id       uint64 `bun:"id,pk,autoincrement"`
	ClientId uint64 `bun:"client_id"`
}
