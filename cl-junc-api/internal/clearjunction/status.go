package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"fmt"
)

func (cj *ClearJunction) GetPaymentStatus(typeId db.TypeDb, clientOrder string) (result *models.PayInPayoutStatusResponse, err error) {
	var name = "payout"

	if typeId == db.INCOMING {
		name = "invoice"
	}

	err = cj.get(&result, "gate", fmt.Sprintf("status/%s/orderReference/%s", name, clientOrder))
	return
}
