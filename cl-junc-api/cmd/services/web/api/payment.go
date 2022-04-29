package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/api/models"
	models2 "cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	models3 "cl-junc-api/internal/redis/models"
	"cl-junc-api/pkg/utils/log"
	"github.com/gin-gonic/gin"
)

const LogKeyCljPaymentRequest = "clearjunction:payment:request:log"

const LogKeyCljPostbackRequest = "clearjunction:postback:request:log"

func Pay(c *gin.Context) {
	request := &models.PaymentRequest{}
	log.Debug().Msgf("payment: Pay: request: %#v", request)
	err := UnmarshalJson(c, LogKeyCljPaymentRequest, request)

	if err != nil {
		log.Error().Err(err)
		return
	}

	dbPayment := &db.Payment{Id: request.PaymentId}

	err = app.Get.Sql.SelectWhereWithRelationResult(dbPayment, []string{"Account", "Status", "Provider", "Type"}, "id")

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

	if dbPayment.Provider.Name == "clearjunction" {
		response := app.Get.Wire.Pay(dbPayment, dbPayee, request.Amount, request.Currency)
		if response != nil {
			app.Get.Redis.AddList(constants.QueueClearJunctionPayLog, response)

			email := &models3.Email{
				Id:      response.CustomFormat.PaymentId,
				Type:    "payin",
				Status:  response.Status,
				Message: "Payin Success",
				Data:    response,
				Details: map[string]string{"test": "", "info": ""},
				Error:   response.Messages,
			}

			app.Get.Redis.AddList(constants.QueueEmailLog, email)

		}
	}
}

//Clearjunction postback
func CljPostback(c *gin.Context) {
	request := &models2.PayInPayoutPostback{}
	err := UnmarshalJson(c, LogKeyCljPostbackRequest, request)

	if err != nil {
		log.Error().Err(err)
		return
	}

	payment := &db.Payment{
		PaymentNumber: request.OrderReference,
	}
	err = app.Get.Sql.SelectWhereResult(&payment, "payment_number")
	if err != nil {
		log.Error().Err(err)
		return
	}

	if request.Status != payment.Status.Name {
		payment := &db.Payment{
			PaymentNumber: request.OrderReference,
			Amount:        request.Amount,
			Status:        &db.Status{Name: request.Status},
		}
		err := app.Get.Sql.Update(payment, "payment_number", "amount_real", "status")
		if err != nil {
			log.Error().Err(err)
			return
		}
		email := &models3.Email{
			Status:  request.Status,
			Data:    request,
			Details: map[string]string{"test": "", "info": ""},
			Error:   request.Messages,
		}

		if request.Type == "payinNotification" {
			email.Type = "payIn-post-back"
			email.Message = "Payin Post Back Success"
		} else if request.Type == "payoutNotification" {
			email.Type = "payout-post-back"
			email.Message = "Payout Post Back Success"
		} else if request.Type == "payoutReturnNotification" {
			email.Type = "payout-post-back"
			email.Message = "Payout Post Back Success"
		}

		app.Get.Redis.AddList(constants.QueueEmailLog, email)

		c.Data(200, "text/plain", []byte(request.OrderReference))
	}

	if !c.IsAborted() {
		c.Data(500, "text/plain", []byte("Wrong Request"))
	}

}
