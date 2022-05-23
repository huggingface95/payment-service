package db

import "github.com/uptrace/bun"

type Payee struct {
	bun.BaseModel `bun:"table:applicant_individual"`

	Id        uint64     `bun:"id,pk,autoincrement"`
	FirstName string     `bun:"first_name"`
	LastName  string     `bun:"last_name"`
	Email     string     `bun:"email"`
	Phone     string     `bun:"phone"`
	Account   []*Account `bun:"m2m:account_individuals_companies,join:Payee=Account"`

	//WalletUuid       string
	//clientCustomerId string
}
