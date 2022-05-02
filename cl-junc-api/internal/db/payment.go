package db

import (
	"github.com/uptrace/bun"
)

type Payment struct {
	bun.BaseModel `bun:"table:payments"`

	Id            uint64       `bun:"id,pk,autoincrement"`
	Amount        float64      `bun:"amount"`
	Name          string       `bun:"sender_name"`
	Email         string       `bun:"sender_email"`
	Phone         string       `bun:"sender_phone"`
	BankAccount   string       `bun:"sender_bank_account"`
	Swift         string       `bun:"sender_swift"`
	BankName      string       `bun:"sender_bank_name"`
	BankCountry   string       `bun:"sender_bank_country"`
	BankAddress   string       `bun:"sender_bank_address"`
	Country       int64        `bun:"sender_country_id"`
	Address       string       `bun:"sender_address"`
	PaymentNumber string       `bun:"payment_number"`
	Error         string       `bun:"error"`
	AccountId     int64        `bun:"account_id"`
	StatusId      int64        `bun:"status_id"`
	ProviderId    int64        `bun:"payment_provider_id"`
	TypeId        int64        `bun:"type_id"`
	Account       *Account     `bun:"rel:belongs-to,join:account_id=id"`
	Status        *Status      `bun:"rel:belongs-to,join:status_id=id"`
	Provider      *Provider    `bun:"rel:belongs-to,join:payment_provider_id=id"`
	Type          *Type        `bun:"rel:belongs-to,join:type_id=id"`
	Transaction   *Transaction `bun:"rel:has-one,join:id=payment_id"`
}
