package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"fmt"
)

const (
	SEPA     = "eu"
	SEPAINST = "sepaInst"
	UKFPS    = "fps"
	CHAPS    = "chaps"
)

func (cj *ClearJunction) CreateExecution(request models.PayoutExecutionRequest, currency db.CurrencyDb) (result *models.PayInPayoutResponse, err error) {

	path := fmt.Sprintf("payout/bankTransfer/%s?checkOnly=%v", getBankType(currency), "true")

	err = cj.post(request, &result, "gate", path)
	return
}

func getBankType(c db.CurrencyDb) string {
	switch c {
	case db.EUR:
		return SEPA
	case db.GBP:
		return UKFPS
	default:
		return SEPA
	}
}
