package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	dbt "cl-junc-api/internal/db"
	"fmt"
	"github.com/gin-gonic/gin"
	"time"
)

const LogKeyPayinRequest = "payin:request:log"

const LogKeyPayinInvoice = "payin:invoice:log"

func PayinCreateInvoice(c *gin.Context) {
	request := &models.PayInInvoiceRequest{
		PostbackUrl: app.Get.Config().App.Url + "/payin/postback",
		SuccessUrl:  app.Get.Config().App.Url + "/payin/postback",
		FailUrl:     app.Get.Config().App.Url + "/payin/postback",
	}

	err := UnmarshalJson(c, LogKeyPayinRequest, request)
	//
	//logData := fmt.Sprintf("[ %v ] %s", time.Now(), "esimmmmm")
	//
	//app.Get.LogRedis(LOG_KEY_PAYIN, logData)
	//
	//fmt.Println("Redisssssssssssssss")
	//fmt.Println(app.Get.Redis.client.Keys(c, "*").Val())
	//fmt.Println(app.Get.Redis.Client.Type(c, LOG_KEY_PAYIN).Val())
	//fmt.Println(app.Get.Redis.Client.LRange(c, LOG_KEY_PAYIN, 0, -1).Val())
	//fmt.Println("Redisssssssssssssss")

	if err == nil {
		logData := fmt.Sprintf("[ %v ] %d %d %s", time.Now(), request.PaymentId, request.Amount, request.Currency)
		app.Get.LogRedis(LogKeyPayinInvoice, logData)
		response, err := app.Get.Wire.CreateInvoice(request)

		app.Get.Log.Info().Msgf("CreateInvoice response: %#v", response)

		if err == nil {
			payment := &dbt.Payment{
				Id:            uint64(request.PaymentId),
				PaymentNumber: response.OrderReference,
			}
			err = app.Get.Sql.Update(payment, "payment_number")

			app.Get.Log.Error().Err(err).Msg("Payment table")

		} else {
			payment := &dbt.Payment{
				Id:     uint64(request.PaymentId),
				Status: response.Status,
			}
			err = app.Get.Sql.Update(payment, "status")

			app.Get.Log.Error().Err(err).Msg("Payment table")
		}
	}

}
