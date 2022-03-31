package db

import (
	"database/sql"
	"github.com/uptrace/bun"
	"time"
)

type Payout struct {
	Id     uint64  `bun:"Id,pk,autoincrement"`
	Amount float64 `bun:"Amount,notnull"`

	CurrencyId CurrencyDb `bun:"CurrencyId,notnull,type:smallint"`
	Currency   *Currency  `bun:"rel:belongs-to,join=CurrencyId=Id"`

	AccountId uint64   `bun:"AccountId,notnull"`
	Account   *Account `bun:"rel:belongs-to,join=AccountId=Id"`

	DateCreated time.Time    `bun:"DateCreated,nullzero,notnull,default:current_timestamp"`
	DateUpdated bun.NullTime `bun:"DateUpdated"`
	Success     sql.NullBool `bun:"Success"`

	TransferType string `bun:"TransferType"`

	Fee float64 `bun:"Fee"`
}
