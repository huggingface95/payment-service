package api

import (
	"cl-junc-api/cmd/app"
	models2 "cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	models3 "cl-junc-api/internal/redis/models"
	"cl-junc-api/pkg/utils/log"
	"github.com/gin-gonic/gin"
)

const LogKeyPayPostbackRequest = "postback:pay:log"
const LogKeyIbanPostbackRequest = "postback:iban:log"

func CljPostback(c *gin.Context) {
	response := &models2.PayInPayoutPostback{}
	err := UnmarshalJson(c, LogKeyPayPostbackRequest, response)

	if err != nil {
		log.Error().Err(err)
		return
	}

	payment := app.Get.GetPaymentWithRelations(&db.Payment{PaymentNumber: response.OrderReference}, []string{"Account", "Status", "Provider", "Type"}, "payment_number")

	if response.Status != payment.Status.Name {
		app.Get.UpdatePayment(&db.Payment{
			PaymentNumber: response.OrderReference,
			Amount:        response.Amount,
			StatusId:      db.GetStatus(response.Status),
		}, "payment_number", "amount_real", "status_id")

		if response.Status == "completed" {
			result, err := app.Get.Wire.PayoutApprove(response.OrderReference)
			if err != nil {
				log.Error().Err(err)
			}

			if len(result.Messages) > 0 {
				return
			}

			var nextBalance = float64(0)
			if payment.TypeId == db.INCOMING {
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

func IbanCompanyCheck(c *gin.Context) {
	id := c.Request.URL.Query().Get("clientCustomerId")
	response, err := app.Get.Wire.GetIbanCompanyStatus(id)
	if err == nil {
		if len(response.Ibans) > 0 {
			c.Data(200, "text/plain", []byte("Successfully"))
		}
	}
	c.Data(404, "text/plain", []byte("Don't iban"))
}

func IbanPostback(c *gin.Context) {
	response := &models2.IbanPostback{}
	err := UnmarshalJson(c, LogKeyIbanPostbackRequest, response)

	if err != nil {
		log.Error().Err(err)
		return
	}

	if response.Status == models2.Accepted {
		app.Get.UpdateAccount(&db.Account{
			AccountState: db.GetAccountState(response.Status),
		}, "payment_number", "account_state")

		c.Data(200, "text/plain", []byte(response.OrderReference))
	}

	if !c.IsAborted() {
		c.Data(500, "text/plain", []byte("Wrong Request"))
	}
}
