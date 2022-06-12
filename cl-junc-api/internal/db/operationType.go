package db

import "github.com/uptrace/bun"

type OperationType struct {
	bun.BaseModel `bun:"table:operation_type"`

	Id   OperationTypeDb `bun:"id,pk,autoincrement"`
	Name string          `bun:"name"`
}

type OperationTypeDb int64

const (
	INCOMING OperationTypeDb = 1
	OUTGOING OperationTypeDb = 2
	DEPOSIT  OperationTypeDb = 3
)

func (c OperationTypeDb) GetTypeName() string {
	switch c {
	case INCOMING:
		return "Incoming Transfer"
	case OUTGOING:
		return "Outgoing Transfer"
	case DEPOSIT:
		return "Deposit"
	}
	return "Outgoing"
}

func GetType(name string) OperationTypeDb {
	switch name {
	case "Incoming":
		return INCOMING
	case "Outgoing":
		return OUTGOING
	case "Fee":
		return DEPOSIT
	}

	return OUTGOING
}
