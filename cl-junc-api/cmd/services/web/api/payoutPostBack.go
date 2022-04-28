package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	models2 "cl-junc-api/internal/redis/models"
	"cl-junc-api/pkg/utils/log"
	"github.com/gin-gonic/gin"
)

const LogKeyPayoutPostBack = "payout-post-back:request:log"

func PayoutPostBack(c *gin.Context) {
	request := &models.PayInPostBack{}
	err := UnmarshalJson(c, LogKeyPayinPostBack, request)

	if err != nil {
		log.Error().Err(err)
		return
	}

	if request.Type == models.PayinNotification {
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
		}

		email := &models2.Email{
			Type:    "payIn-post-back",
			Status:  request.Status,
			Message: "Payin Post Back Success",
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
