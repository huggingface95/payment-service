package db

import "github.com/uptrace/bun"

type Payee struct {
	bun.BaseModel `bun:"table:applicant_individual"`

	Id        uint64
	FirstName string `bun:"first_name"`
	LastName  string `bun:"last_name"`
	Email     string `bun:"email"`
	Phone     string `bun:"phone"`

	//WalletUuid       string
	//clientCustomerId string
}
