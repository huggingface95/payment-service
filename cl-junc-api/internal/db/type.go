package db

import "github.com/uptrace/bun"

type Type struct {
	bun.BaseModel `bun:"table:payment_types"`

	Id   TypeDb `bun:"id,pk,autoincrement"`
	Name string `bun:"name"`
}

type TypeDb int64

const (
	INCOMING TypeDb = 1
	OUTGOING TypeDb = 2
	FEE      TypeDb = 3
)

func (c TypeDb) GetTypeName() string {
	switch c {
	case INCOMING:
		return "Incoming"
	case OUTGOING:
		return "Outgoing"
	case FEE:
		return "Fee"
	}
	return "Outgoing"
}

func GetType(name string) TypeDb {
	switch name {
	case "Incoming":
		return INCOMING
	case "Outgoing":
		return OUTGOING
	case "Fee":
		return FEE
	}

	return OUTGOING
}
