package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	dbt "cl-junc-api/internal/db"
	"fmt"
	"github.com/gin-gonic/gin"
)

const LogKeyPayinPostBack = "payin-post-back:request:log"

//const LogKeyPayinInvoice = "payin:invoice:log"

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
			payment := &dbt.Payment{
				PaymentNumber: request.OrderReference,
			}
			err = app.Get.Sql.SelectWhereResult(&payment, "payment_number")
			if err != nil {
				app.Get.Log.Error().Err(err)
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
					app.Get.Log.Error().Err(err)
					return
				} else {
					app.Mail(
						fmt.Sprintf("%s", "PAYIN POST BACK"),
						fmt.Sprintf("%s", "SUCESS"),
						map[string]string{"number": payment.PaymentNumber},
						request,
					)
				}
			}

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
