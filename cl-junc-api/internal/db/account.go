package db

import (
	"github.com/uptrace/bun"
	"time"
)

type Account struct {
	Id               uint64 `bun:"Id,pk,autoincrement"`
	Number           string `bun:"Number,notnull,unique"`
	OwnerProfileUUID string `bun:"OwnerProfileUUID,notnull"`
	Iban             string `bun:"Iban"`

	CurrencyId CurrencyDb `bun:"CurrencyId,notnull,type:smallint"`
	Currency   *Currency  `bun:"rel:belongs-to,join=CurrencyId=Id"`

	Swift       string       `bun:"Swift"`
	DateCreated time.Time    `bun:"DateCreated,nullzero,notnull,default:current_timestamp"`
	DateUpdated bun.NullTime `bun:"DateUpdated"`
}
