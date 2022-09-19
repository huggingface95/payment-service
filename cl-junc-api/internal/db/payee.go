package db

import "github.com/uptrace/bun"

type Payee struct {
	bun.BaseModel `bun:"table:applicant_individual"`

	Id          uint64 `bun:"id,pk,autoincrement"`
	FirstName   string `bun:"first_name"`
	LastName    string `bun:"last_name"`
	Email       string `bun:"email"`
	Phone       string `bun:"phone"`
	State       string `bun:"state"`
	City        string `bun:"phone"`
	Zip         string `bun:"zip"`
	Address     string `bun:"address"`
	Nationality string `bun:"nationality"`

	//Account []*Account `bun:"m2m:account_individuals_companies,join:Payee=Account"`

	//WalletUuid       string
	//clientCustomerId string
}
