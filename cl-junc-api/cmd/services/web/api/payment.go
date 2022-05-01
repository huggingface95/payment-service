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
		payResponse := app.Get.Wire.Pay(dbPayment, dbPayee, request.Amount, request.Currency)

		pRef := &db.Payment{
			Id:            dbPayment.Id,
			PaymentNumber: payResponse.OrderReference,
		}
		err := app.Get.Sql.Update(pRef, "id", "payment_number")

		if err != nil {
			log.Error().Err(err)
			return
		}
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

	payment := &db.Payment{
		PaymentNumber: response.OrderReference,
	}

	err = app.Get.Sql.SelectWhereWithRelationResult(payment, []string{"Account", "Status", "Provider", "Type"}, "payment_number")
	if err != nil {
		log.Error().Err(err)
		return
	}

	if response.Status != payment.Status.Name {
		status := app.Get.GetStatusByName(response.Status)

		upPayment := &db.Payment{
			PaymentNumber: response.OrderReference,
			Amount:        response.Amount,
			StatusId:      int64(status.Id),
		}
		err := app.Get.Sql.Update(upPayment, "payment_number", "amount_real", "status_id")
		if err != nil {
			log.Error().Err(err)
			return
		}

		if response.Status == "completed" {
			var nextBalance = float64(0)
			if payment.Type.Name == "payIn" {
				nextBalance = payment.Account.CurrentBalance + response.Amount
			} else {
				nextBalance = payment.Account.CurrentBalance - response.Amount
			}
			transaction := db.Transaction{
				PaymentId:   payment.Id,
				Amount:      response.Amount,
				BalancePrev: payment.Account.CurrentBalance,
				BalanceNext: nextBalance,
			}

			account := db.Account{
				CurrentBalance: nextBalance,
			}

			err := app.Get.Sql.Insert(transaction)
			if err != nil {
				return
			}

			err = app.Get.Sql.Insert(account)
			if err != nil {
				return
			}

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
