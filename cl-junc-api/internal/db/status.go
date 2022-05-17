package db

import (
	"github.com/uptrace/bun"
)

type Status struct {
	bun.BaseModel `bun:"table:payment_status"`

	Id   StatusDb `bun:"id,pk,autoincrement"`
	Name string   `bun:"name"`
}

type StatusDb uint64

const (
	PENDING   StatusDb = 1
	COMPLITED StatusDb = 2
	ERROR     StatusDb = 3
	CANCELED  StatusDb = 4
	UNSIGNED  StatusDb = 5
	CREATED   StatusDb = 6
)

func (s StatusDb) GetStatusName() string {
	switch s {
	case PENDING:
		return "pending"
	case COMPLITED:
		return "complited"
	case ERROR:
		return "error"
	case CANCELED:
		return "canceled"
	case UNSIGNED:
		return "unsigned"
	case CREATED:
		return "created"
	}

	return "error"
}

func GetStatus(name string) StatusDb {
	switch name {
	case "Pending":
		return PENDING
	case "Complited":
		return COMPLITED
	case "Error":
		return ERROR
	case "Canceled":
		return CANCELED
	case "Unsigned":
		return UNSIGNED
	case "Created":
		return CREATED
	}

	return ERROR
}
