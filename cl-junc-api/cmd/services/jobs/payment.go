package jobs

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	"cl-junc-api/internal/redis/models"
	"cl-junc-api/pkg/utils/log"
	"encoding/json"
)

func ProcessPayQueue() {
	for {
		redisData := app.Get.GetRedisDataByBlPop(constants.QueuePayLog, func() interface{} {
			return new(models.PaymentRequest)
		})
		if redisData == nil {
			break
		}
		pay(redisData.(*models.PaymentRequest))
	}
}

func pay(request *models.PaymentRequest) {

	dbPayment := app.Get.GetPaymentWithRelations(&db.Payment{Id: request.Id}, []string{"Account.Payee", "Status", "Provider", "Type", "Currency"}, "id")

	if dbPayment.Provider.Name == db.CLEARJUNCTION {
		payResponse := app.Get.Wire.Pay(dbPayment, request.Amount, request.Currency)
		if payResponse == nil {
			return
		}
		statusResponse, err := app.Get.Wire.GetPaymentStatus(dbPayment.TypeId, payResponse.OrderReference)
		if err != nil {
			log.Error().Err(err)
			return
		}
		if len(statusResponse.Messages) == 0 {
			dbPayment.StatusId = db.GetStatus(statusResponse.Status)
			dbPayment.PaymentNumber = statusResponse.OrderReference
			app.Get.UpdatePayment(dbPayment, "payment_number", "status_id", "payment_number")
			email := &models.Email{
				Id:      int64(dbPayment.Id),
				Status:  statusResponse.Status,
				Message: "Payment change status",
				Data:    statusResponse,
			}
			app.Get.Redis.AddList(constants.QueueEmailLog, email)
		} else {
			marshalMessage, err := json.Marshal(statusResponse.Messages)
			if err != nil {
				log.Error().Err(err)
				return
			}
			dbPayment.Error = string(marshalMessage)
			app.Get.UpdatePayment(dbPayment, "payment_number", "error")
		}
	}
}
