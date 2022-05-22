package db

import "github.com/uptrace/bun"

type AccountIndividualsCompanies struct {
	bun.BaseModel `bun:"table:account_individuals_companies"`

	AccountID uint64   `bun:"account_id,pk"`
	Account   *Account `bun:"rel:belongs-to,join:account_id=id"`
	ClientId  uint64   `bun:"client_id,pk"`
	Payee     *Payee   `bun:"rel:belongs-to,join:client_id=id"`
}
