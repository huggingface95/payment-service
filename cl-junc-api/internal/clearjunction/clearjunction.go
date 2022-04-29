package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"cl-junc-api/pkg/utils/log"
	"errors"
)

type ClearJunction struct {
	config  models.Config
	baseUrl string
}

func New(config models.Config, url string) *ClearJunction {
	return &ClearJunction{config: config, baseUrl: url}
}

func (cj *ClearJunction) Pay(payment *db.Payment, payee *db.Payee, amount float64, currency string) *models.PayInPayoutResponse {

	payInPayoutRequest := models.NewPayInPayoutRequest(payment, payee, amount, currency, cj.Wallet())

	response := &models.PayInPayoutResponse{}
	err := errors.New("")
	if payment.Type.Name == "payIn" {
		request := models.NewPayInInvoiceRequest(payInPayoutRequest, cj.baseUrl)
		response, err = cj.CreateInvoice(request)
	} else {
		request := models.NewPayoutExecutionRequest(payInPayoutRequest, cj.baseUrl)
		response, err = cj.CreateExecution(request, payment.BankName)
	}

	if err != nil {
		log.Error().Err(err)
		return nil
	}

	return response

}

func (cj *ClearJunction) Wallet() string {
	return cj.config.Wallet
}
