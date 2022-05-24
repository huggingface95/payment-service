package db

import "github.com/uptrace/bun"

type Account struct {
	bun.BaseModel `bun:"table:accounts"`

	Id               uint64         `bun:"id,pk,autoincrement"`
	Iban             string         `bun:"account_number"`
	OrderReference   string         `bun:"order_reference"`
	AvailableBalance float64        `bun:"available_balance"`
	CurrentBalance   float64        `bun:"current_balance"`
	ReservedBalance  float64        `bun:"reserved_balance"`
	Payee            []*Payee       `bun:"m2m:account_individuals_companies,join:Account=Payee"`
	AccountState     AccountStateDb `bun:"account_state"`
	State            *AccountState  `bun:"rel:belongs-to,join:account_state=id"`
}

func (a *Account) GetPayee() *Payee {
	return a.Payee[0]
}
