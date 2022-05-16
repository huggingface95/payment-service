package db

import "github.com/uptrace/bun"

type AccountState struct {
	bun.BaseModel `bun:"table:account_states"`

	Id   uint64 `bun:"id,pk,autoincrement"`
	Name string `bun:"name"`
}
