package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
)

const (
	SWIFT   string = "swift"
	FEDWIRE string = "fedwire"
)

func (cj *ClearJunction) CreateExecution(request *db.Payout) (result models.PayoutExecutionResponse, err error) {
	err = cj.post(request, &result, "gate", "payout/bankTransfer/"+getBankType(request.BankType)+"?checkOnly=false")
	return
}

func getBankType(paymentType string) string {
	switch paymentType {
	case "fedwire":
		return FEDWIRE
	default:
		return SWIFT
	}
}
