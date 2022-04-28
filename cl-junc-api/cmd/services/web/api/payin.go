package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/api/models"
	models2 "cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	"cl-junc-api/pkg/utils/log"
	"github.com/gin-gonic/gin"
)

const LogKeyPayInRequest = "payin:request:log"

func PayInCreateInvoice(c *gin.Context) {
	payInRequest := &models.PaymentRequest{}
	log.Debug().Msgf("payment: PayInCreateInvoice: request: %#v", payInRequest)
	err := UnmarshalJson(c, LogKeyPayInRequest, payInRequest)

	if err != nil {
		log.Error().Err(err)
		return
	}

	dbPayment := &db.Payment{Id: payInRequest.PaymentId}

	err = app.Get.Sql.SelectWhereWithRelationResult(dbPayment, []string{"Account", "Status"}, "id")

	if err != nil {
		log.Error().Err(err)
		return
	}

	dbPayee := &db.Payee{Id: dbPayment.Account.ClientId}

	err = app.Get.Sql.SelectResult(dbPayee)

	if err != nil {
		log.Error().Err(err)
		return
	}

	payInPayoutRequest := models2.NewPayInPayoutRequest(payInRequest, dbPayment, dbPayee)

	invoiceRequest := models2.NewPayInInvoiceRequest(payInPayoutRequest, app.Get.Config().App.Url)

	response, _ := app.Get.Wire.CreateInvoice(invoiceRequest)

	app.Get.Redis.AddList(constants.QueuePayInLog, response)

	return

}
