package db

import (
	"github.com/uptrace/bun"
	"time"
)

type Payin struct {
	Id         uint64     `bun:"Id,pk,autoincrement"`
	Amount     float64    `bun:"Amount,notnull"`
	CurrencyId CurrencyDb `bun:"CurrencyId,notnull,type:smallint"`
	Currency   *Currency  `bun:"rel:belongs-to,join=CurrencyId=Id"`

	AccountId uint64   `bun:"AccountId,notnull"`
	Account   *Account `bun:"rel:belongs-to,join=AccountId=Id"`

	RefId       string       `bun:"RefId,unique"`
	DateCreated time.Time    `bun:"DateCreated,nullzero,notnull,default:current_timestamp"`
	DateUpdated bun.NullTime `bun:"DateUpdated"`

	TransferType string `bun:"TransferType"`

	Fee float64 `bun:"Fee"`
}
