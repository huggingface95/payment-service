package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
	"fmt"
)

func (cj *ClearJunction) GetPaymentStatus(paymentType string, clientOrder string) (result *models.PayInPayoutStatusResponse, err error) {
	var name = "payout"

	if paymentType == "payIn" {
		name = "invoice"
	}

	err = cj.get(&result, "gate", fmt.Sprintf("status/%s/orderReference/%s", name, clientOrder))
	return
}
