package db

import (
	"github.com/uptrace/bun"
)

type AccountState struct {
	bun.BaseModel `bun:"table:account_states"`

	Id   AccountStateDb `bun:"id,pk,autoincrement"`
	Name string         `bun:"name"`
}

type AccountStateDb uint64

const (
	StateWaitingForApproval              AccountStateDb = 1
	StateWaitingForAccountIbanGeneration AccountStateDb = 2
	StateAwaitingAccount                 AccountStateDb = 3
	StateActive                          AccountStateDb = 4
	StateClosed                          AccountStateDb = 5
	StateSuspended                       AccountStateDb = 6
	StateRejected                        AccountStateDb = 7
)

func (a AccountStateDb) GetAccountStateName() string {
	switch a {
	case StateWaitingForApproval:
		return "Waiting for approval"
	case StateWaitingForAccountIbanGeneration:
		return "Waiting for Account# Generation"
	case StateAwaitingAccount:
		return "Awaiting Account#"
	case StateActive:
		return "Active"
	case StateClosed:
		return "Closed"
	case StateSuspended:
		return "Suspended"
	case StateRejected:
		return "Rejected"
	}

	return ""
}

func GetAccountState(name string) AccountStateDb {
	switch name {
	case "Waiting for approval":
		return StateWaitingForApproval
	case "Waiting for Account# Generation":
		return StateWaitingForAccountIbanGeneration
	case "Awaiting Account#":
		return StateAwaitingAccount
	case "Active":
		return StateActive
	case "Closed":
		return StateClosed
	case "Suspended":
		return StateSuspended
	case "Rejected":
		return StateRejected
	}

	return 0
}
