package jobs

import (
	"cl-junc-api/cmd/app"
	models2 "cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	"cl-junc-api/internal/redis/models"
	"cl-junc-api/pkg/utils/log"
	"encoding/json"
)

func ProcessPayQueue() {
	workCljPayments()
}

func workCljPayments() {
	for {
		redisData := app.Get.GetRedisDataByBlPop(constants.QueueClearJunctionPayLog, func() interface{} {
			return new(models2.PayInPayoutResponse)
		})
		if redisData == nil {
			break
		}
		payClj(redisData.(*models2.PayInPayoutResponse))
	}
}

func payClj(response *models2.PayInPayoutResponse) {

	dbPayment := app.Get.GetPayment(&db.Payment{PaymentNumber: response.OrderReference}, "payment_number")

	changeStatusResponse, err := app.Get.Wire.GetPaymentStatus(dbPayment.TypeId, response.OrderReference)

	if err != nil {
		log.Error().Err(err)
		return
	}

	if len(changeStatusResponse.Messages) == 0 {
		status := app.Get.GetStatusByName(response.Status)
		dbPayment.StatusId = int64(status.Id)
		app.Get.UpdatePayment(dbPayment, "payment_number", "status_id")
		if err == nil {
			email := &models.Email{
				Id:      int64(dbPayment.Id),
				Status:  response.Status,
				Message: "Payment change status",
				Data:    response,
			}
			app.Get.Redis.AddList(constants.QueueEmailLog, email)
		}
	} else {
		marshalMessage, err := json.Marshal(response.Messages)

		if err != nil {
			log.Error().Err(err)
			return
		}
		dbPayment.Error = string(marshalMessage)

		app.Get.UpdatePayment(dbPayment, "payment_number", "status_id")
	}

	return
}
