package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
)

func (cj *ClearJunction) CreateInvoice(request models.PayInInvoiceRequest) (result *models.PayInPayoutResponse, err error) {
	err = cj.post(request, &result, "gate", "invoice/creditCard")
	return
}
