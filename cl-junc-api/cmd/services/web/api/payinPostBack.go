package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	models2 "cl-junc-api/internal/redis/models"
	"github.com/gin-gonic/gin"
)

const LogKeyPayinPostBack = "payin-post-back:request:log"

func PayinPostBack(c *gin.Context) {

	request := &models.PayInPostBack{}
	err := UnmarshalJson(c, LogKeyPayinPostBack, request)

	if err != nil {
		app.Get.Log.Error().Err(err)
		return
	}
	app.Get.Log.Info().Msgf("UnmarshalJson data: %s", request)

	if err == nil {
		if request.Type == models.PayinNotification {
			payment := &db.Payment{
				PaymentNumber: request.OrderReference,
			}
			err = app.Get.Sql.SelectWhereResult(&payment, "payment_number")
			if err != nil {
				app.Get.Log.Error().Err(err)
				return
			}

			if request.Status != payment.PaymentNumber {
				payment := &db.Payment{
					PaymentNumber: request.OrderReference,
					Amount:        request.Amount,
					Status:        request.Status,
				}
				err := app.Get.Sql.Update(payment, "amount_real", "status")
				if err != nil {

					return
				} else {

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

			app.Get.Redis.AddList(constants.QueuePayInPostBackLog, email)

			c.Data(200, "text/plain", []byte(request.OrderReference))
			return
		}

	} else {
		app.Get.Log.Error().Err(err)
	}

	if !c.IsAborted() {
		c.Data(500, "text/plain", []byte("Wrong request"))
	}
}
