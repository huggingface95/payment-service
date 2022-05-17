package db

import (
	"github.com/uptrace/bun"
)

type AccountState struct {
	bun.BaseModel `bun:"table:account_states"`

	Id   AccountStateDb `bun:"id,pk,autoincrement"`
	Name string         `bun:"name"`
}

type AccountStateDb int64

const (
	StateActive    AccountStateDb = 1
	StateSuspended AccountStateDb = 2
	StateBlocked   AccountStateDb = 3
	StatePending   AccountStateDb = 4
	StateClosed    AccountStateDb = 5
)

func (a AccountStateDb) GetAccountStateName() string {
	switch a {
	case StateActive:
		return "Active"
	case StateSuspended:
		return "Suspended"
	case StateBlocked:
		return "Blocked"
	case StatePending:
		return "Pending"
	case StateClosed:
		return "Closed"
	}

	return "error"
}

func GetAccountState(name string) AccountStateDb {
	switch name {
	case "active":
		return StateActive
	case "suspended":
		return StateSuspended
	case "blocked":
		return StateBlocked
	case "pending":
		return StatePending
	case "closed":
		return StateClosed
	}

	return StateBlocked
}
