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

	fmt.Println("stexaaaaaaaaaaaaa")

	request := &models.PayInPostBack{}
	err := UnmarshalJson(c, LogKeyPayinPostBack, request)

	if err == nil {

		if request.Type == models.PayinNotification {

			payment := &dbt.Payment{
				PaymentNumber: request.OrderReference,
			}
			//var m map[string]interface{}
			err = app.Get.Sql.SelectWhereResult(&payment, "payment_number")
			if err != nil {
				return
			}
			//status := fmt.Sprintln("%s", m)

			if request.Status != payment.PaymentNumber {
				payment := &dbt.Payment{
					PaymentNumber: request.OrderReference,
					Amount:        request.Amount,
					Status:        request.Status,
				}
				err := app.Get.Sql.Update(payment, "amount_real", "status")
				if err != nil {
					return
				}
			}

			c.Data(200, "text/plain", []byte(request.OrderReference))
			return
		}

	}

	if !c.IsAborted() {
		c.Data(500, "text/plain", []byte("Wrong request"))
	}
}
