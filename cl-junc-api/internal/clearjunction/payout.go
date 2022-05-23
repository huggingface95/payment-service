package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"errors"
	"fmt"
)

const (
	SEPA     = "eu"
	SEPAINST = "sepaInst"
	UKFPS    = "fps"
	CHAPS    = "chaps"
)

func (cj *ClearJunction) CreateExecution(request models.PayoutExecutionRequest, currency db.CurrencyDb) (result *models.PayInPayoutResponse, err error) {

	path := fmt.Sprintf("payout/bankTransfer/%s?checkOnly=%v", getBankType(currency), "false")

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

func (cj *ClearJunction) PayoutApprove(orderReference string) (result models.ApproveResult, err error) {
	response := models.PayoutApproveResponse{}
	err = cj.post(map[string]interface{}{"orderReferenceArray": []string{orderReference}}, &response, "gate", "transactionAction/approve")
	if err == nil {
		if len(response.ActionResult) > 0 {
			result = response.ActionResult[0]
		} else {
			err = errors.New("empty result")
		}
	}
	return
}
