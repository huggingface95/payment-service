package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	dbt "cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	models2 "cl-junc-api/internal/redis/models"
	"github.com/gin-gonic/gin"
	"github.com/rs/zerolog/log"
)

const LogKeyPayoutPostBack = "payout-post-back:request:log"

func PayoutPostBack(c *gin.Context) {
	request := &models.PayInPostBack{}
	err := UnmarshalJson(c, LogKeyPayoutPostBack, request)

	if err != nil {
		log.Error().Err(err)
		return
	}

	if request.Type == models.PayinNotification {

		payment := &dbt.Payment{
			PaymentNumber: request.OrderReference,
		}

		err = app.Get.Sql.SelectWhereResult(&payment, "payment_number")
		if err != nil {
			log.Error().Err(err)
			return
		}

		if request.Status != payment.PaymentNumber {
			payment := &dbt.Payment{
				PaymentNumber: request.OrderReference,
				Amount:        request.Amount,
				Status:        request.Status,
			}
			err := app.Get.Sql.Update(payment, "amount_real", "status")
			if err != nil {
				log.Error().Err(err)
				return
			} else {
			}
		}

		email := &models2.Email{
			Type:    "payout-post-back",
			Status:  request.Status,
			Message: "Payout Post Back Success",
			Data:    request,
			Details: map[string]string{"test": "", "info": ""},
			Error:   request.Messages,
		}

		app.Get.Redis.AddList(constants.QueuePayoutPostBackLog, email)

		c.Data(200, "text/plain", []byte(request.OrderReference))
		return
	}

	if !c.IsAborted() {
		c.Data(500, "text/plain", []byte("Wrong request"))
	}
}
