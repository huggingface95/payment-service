package api

import (
	"cl-junc-api/cmd/app"
	models2 "cl-junc-api/internal/api/models"
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	"cl-junc-api/pkg/utils/log"
	"github.com/gin-gonic/gin"
)

const LogKeyPayoutRequest = "payout:request:log"

func PayoutExecution(c *gin.Context) {
	payoutRequest := &models2.PaymentRequest{}
	log.Debug().Msgf("payment: PayoutExecution: request: %#v", payoutRequest)
	err := UnmarshalJson(c, LogKeyPayoutRequest, payoutRequest)

	if err != nil {
		log.Error().Err(err)
		return
	}

	dbPayment := &db.Payment{Id: payoutRequest.PaymentId}

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

	payInPayoutRequest := models.NewPayInPayoutRequest(payoutRequest, dbPayment, dbPayee)

	executionRequest := models.NewPayoutExecutionRequest(payInPayoutRequest, app.Get.Config().App.Url)

	response, err := app.Get.Wire.CreateExecution(executionRequest)

	app.Get.Redis.AddList(constants.QueuePayInLog, response)

	return
}
