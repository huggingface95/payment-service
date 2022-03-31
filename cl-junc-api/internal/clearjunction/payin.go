package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
)

const (
	CARD   string = "creditCard"
	QIWI   string = "qiwi"
	YANDEX string = "yandexMoney"
)

func (cj *ClearJunction) CreateInvoice(request *models.PayInInvoiceRequest) (result models.PayInInvoiceResponse, err error) {
	err = cj.post(request, &result, "gate", "invoice/"+getPaymentType(request.PaymentType))
	return
}

func getPaymentType(paymentType string) string {
	switch paymentType {
	case "qiwi":
		return QIWI
	case "yandex":
		return YANDEX
	default:
		return CARD
	}
}
