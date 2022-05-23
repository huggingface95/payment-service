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

func (cj *ClearJunction) Pay(payment *db.Payment, amount float64, currency string) *models.PayInPayoutResponse {

	payInPayoutRequest := models.NewPayInPayoutRequest(payment, amount, currency, cj.Wallet())

	response := &models.PayInPayoutResponse{}
	err := errors.New("")
	if payment.TypeId == db.INCOMING {
		request := models.NewPayInInvoiceRequest(payInPayoutRequest, cj.baseUrl)
		response, err = cj.CreateInvoice(request)
	} else {
		request := models.NewPayoutExecutionRequest(payInPayoutRequest, cj.baseUrl)
		response, err = cj.CreateExecution(request, payment.CurrencyId)
	}

	if err != nil {
		log.Error().Err(err)
		return nil
	}

	return response
}

func (cj *ClearJunction) Iban(account *db.Account) *models.IbanCreateResponse {
	ibanRequest := models.NewIbanRequest(account, cj.Wallet(), cj.baseUrl)
	response, err := cj.CreateIban(ibanRequest)
	if err != nil {
		log.Error().Err(err)
		return nil
	}
	return response
}

func (cj *ClearJunction) Wallet() string {
	return cj.config.Wallet
}
