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

	dbPayment := app.Get.GetPaymentWithRelations(&db.Payment{Id: request.PaymentId}, []string{"Account", "Status", "Provider", "Type"}, "id")

	dbPayee := app.Get.GetPayee(&db.Payee{Id: dbPayment.Account.ClientId}, "id")

	if dbPayment.Provider.Name == "clearjunction" {
		payResponse := app.Get.Wire.Pay(dbPayment, dbPayee, request.Amount, request.Currency)
		app.Get.UpdatePayment(&db.Payment{Id: dbPayment.Id, PaymentNumber: payResponse.OrderReference}, "id", "payment_number")
		app.Get.Redis.AddList(constants.QueueClearJunctionPayLog, payResponse)
	}
}

func CljPostback(c *gin.Context) {
	response := &models2.PayInPayoutPostback{}
	err := UnmarshalJson(c, LogKeyCljPostbackRequest, response)

	if err != nil {
		log.Error().Err(err)
		return
	}

	payment := app.Get.GetPaymentWithRelations(&db.Payment{PaymentNumber: response.OrderReference}, []string{"Account", "Status", "Provider", "Type"}, "payment_number")

	if response.Status != payment.Status.Name {
		status := app.Get.GetStatusByName(response.Status)

		app.Get.UpdatePayment(&db.Payment{
			PaymentNumber: response.OrderReference,
			Amount:        response.Amount,
			StatusId:      int64(status.Id),
		}, "payment_number", "amount_real", "status_id")

		if response.Status == "completed" {
			var nextBalance = float64(0)
			if payment.Type.Name == "payIn" {
				nextBalance = payment.Account.CurrentBalance + response.Amount
			} else {
				nextBalance = payment.Account.CurrentBalance - response.Amount
			}

			_ = app.Get.CreateTransaction(&db.Transaction{
				PaymentId:   payment.Id,
				Amount:      response.Amount,
				BalancePrev: payment.Account.CurrentBalance,
				BalanceNext: nextBalance,
			})

			app.Get.UpdateAccount(&db.Account{Id: payment.Account.Id, CurrentBalance: nextBalance}, "id", "current_balance")
		}

		email := &models3.Email{
			Id:      int64(payment.Id),
			Status:  response.Status,
			Message: "Payment postback status",
			Data:    response,
		}

		app.Get.Redis.AddList(constants.QueueEmailLog, email)

		c.Data(200, "text/plain", []byte(response.OrderReference))
	}

	if !c.IsAborted() {
		c.Data(500, "text/plain", []byte("Wrong Request"))
	}

}
