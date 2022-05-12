package db

import "github.com/uptrace/bun"

type Currency struct {
	bun.BaseModel `bun:"table:currencies"`
	Id            CurrencyDb `bun:"id,pk,autoincrement"`
	Code          string     `bun:"code"`
}

type CurrencyDb int64

const (
	EUR CurrencyDb = 1
	GBP CurrencyDb = 3
)

func (c CurrencyDb) GetCurrencyCode() string {
	switch c {
	case EUR:
		return "EUR"
	case GBP:
		return "GBP"
	}

	return "EUR"
}

func GetCurrency(currencyCode string) CurrencyDb {
	switch currencyCode {
	case "EUR":
		return EUR
	case "GBP":
		return GBP
	}

	return EUR
}
