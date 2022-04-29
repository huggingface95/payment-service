package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
	"fmt"
)

const (
	Swift    = "swift"
	Fedwire  = "fedwire"
	Sepa     = "eu"
	SepaInst = "sepaInst"
	Ru       = "ru"
	Md       = "md"
	UkFps    = "fps"
	Internal = "internalPayment"
)

func (cj *ClearJunction) CreateExecution(request models.PayoutExecutionRequest, paymentType string) (result *models.PayInPayoutResponse, err error) {

	path := fmt.Sprintf("payout/%s?checkOnly=%v", getBankType(paymentType), "true")

	err = cj.post(request, &result, "gate", path)
	return
}

func getBankType(paymentType string) string {
	switch paymentType {
	case "swift":
		return Swift
	case "fedwire":
		return Fedwire
	case "eu":
		return Sepa
	case "sepaInst":
		return SepaInst
	case "ru":
		return Ru
	case "md":
		return Md
	case "fps":
		return UkFps
	case "internalPayment":
		return Internal
	default:
		return Swift
	}
}
