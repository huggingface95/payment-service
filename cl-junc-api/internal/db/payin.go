package db

import (
	"github.com/uptrace/bun"
	"time"
)

type Payin struct {
	Id         uint64     `bun:"id,pk,autoincrement"`
	Amount     float64    `bun:"amount,notnull"`
	CurrencyId CurrencyDb `bun:"currency_Id,notnull,type:smallint"`
	Currency   *Currency  `bun:"rel:belongs-to,join=CurrencyId=Id"`

	AccountId uint64   `bun:"AccountId,notnull"`
	Account   *Account `bun:"rel:belongs-to,join=AccountId=Id"`

	RefId       string       `bun:"RefId,unique"`
	DateCreated time.Time    `bun:"DateCreated,nullzero,notnull,default:current_timestamp"`
	DateUpdated bun.NullTime `bun:"DateUpdated"`

	TransferType string `bun:"TransferType"`

	Fee float64 `bun:"Fee"`

	PaymentType string `bun:"payment_type"`

	PostbackUrl string
	SuccessUrl  string
	FailUrl     string
}
