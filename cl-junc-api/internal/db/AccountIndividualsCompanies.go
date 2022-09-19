package db

import "github.com/uptrace/bun"

type AccountIndividualsCompanies struct {
	bun.BaseModel `bun:"table:account_individuals_companies"`

	AccountId uint64   `bun:",pk"`
	Account   *Account `bun:"rel:belongs-to,join:account_id=id"`
	ClientId  uint64   `bun:",pk"`
	Payee     *Payee   `bun:"rel:belongs-to,join:client_id=id"`
}
