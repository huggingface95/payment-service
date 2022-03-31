package db

import "github.com/uptrace/bun"

type Currency struct {
	bun.BaseModel `bun:"table:currencies"`
	Id            CurrencyDb `bun:"Id,pk,autoincrement,type:smallint"`
	Name          string     `bun:"string,notnull,unique"`
}

type CurrencyDb uint16

const (
	EUR CurrencyDb = 1
	GBP CurrencyDb = 2
	RUB CurrencyDb = 3
)

func (c CurrencyDb) GetCurrencyCode() string {
	switch c {
	case EUR:
		return "EUR"
	case GBP:
		return "GBP"
	case RUB:
		return "RUB"
	}

	return "EUR"
}

func GetCurrency(currencyCode string) CurrencyDb {
	switch currencyCode {
	case "EUR":
		return EUR
	case "GBP":
		return GBP
	case "RUB":
		return RUB
	}

	return EUR
}
