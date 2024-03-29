package db

import (
	"github.com/uptrace/bun"
)

type Account struct {
	bun.BaseModel `bun:"table:accounts"`

	Id               uint64         `bun:"id,pk,autoincrement"`
	ClientId         uint64         `bun:"client_id"`
	Iban             string         `bun:"account_number"`
	OrderReference   string         `bun:"order_reference"`
	AvailableBalance float64        `bun:"available_balance"`
	CurrentBalance   float64        `bun:"current_balance"`
	ReservedBalance  float64        `bun:"reserved_balance"`
	Payee            Payee          `bun:"rel:belongs-to,join:client_id=id"`
	AccountState     AccountStateDb `bun:"account_state_id"`
	ProviderId       ProviderDb     `bun:"payment_provider_id"`
	State            *AccountState  `bun:"rel:belongs-to,join:account_state_id=id"`
	Provider         *Provider      `bun:"rel:belongs-to,join:payment_provider_id=id"`
}

func (a *Account) GetPayee() Payee {
	return a.Payee
}
