package clearjunction

import "cl-junc-api/internal/clearjunction/models"

const (
	SWIFT   string = "swift"
	FEDWIRE string = "fedwire"
)

func (cj *ClearJunction) CreateExecution(request *models.PayoutExecutionRequest) (result models.PayoutExecutionResponse, err error) {
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
