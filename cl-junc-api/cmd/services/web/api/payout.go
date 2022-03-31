package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	dbt "cl-junc-api/internal/db"
	"fmt"
	"github.com/gin-gonic/gin"
	"time"
)

const LogKeyPayoutRequest = "payout:request:log"

const LogKeyPayoutExecution = "payout:execution:log"

func PayoutExecution(c *gin.Context) {
	request := &models.PayoutExecutionRequest{
		PostbackUrl: app.Get.Config().App.Url + "/payout/postback",
	}
	err := UnmarshalJson(c, LogKeyPayoutRequest, request)

	if err == nil {
		logData := fmt.Sprintf("[ %v ] %d %s %s", time.Now(), request.PaymentId, request.Amount, request.Currency)
		app.Get.LogRedis(LogKeyPayoutExecution, logData)

		response, err := app.Get.Wire.CreateExecution(request)
		if err == nil {
			payment := &dbt.Payment{
				Id:            uint64(request.PaymentId),
				PaymentNumber: response.OrderReference,
			}
			err = app.Get.Sql.Update(payment, "payment_number")
		} else {
			payment := &dbt.Payment{
				Id:     uint64(request.PaymentId),
				Status: response.Status,
			}
			err = app.Get.Sql.Update(payment, "status")
		}
	}

}
