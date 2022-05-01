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

	cljPayList := getCljPayments()

	for _, p := range cljPayList {
		payClj(p)
	}
}

func getCljPayments() []*models2.PayInPayoutResponse {
	redisList := app.Get.GetRedisList(constants.QueueClearJunctionPayLog, func() interface{} {
		return new(models2.PayInPayoutResponse)
	})

	log.Debug().Msgf("jobs: getCljPayments: redisList: %#v", redisList)

	var newList []*models2.PayInPayoutResponse

	for _, c := range redisList {
		newList = append(newList, c.(*models2.PayInPayoutResponse))
	}

	log.Debug().Msgf("jobs: getCljPayments: newList: %#v", newList)

	return newList
}

func payClj(response *models2.PayInPayoutResponse) {
	dbPayment := &db.Payment{
		PaymentNumber: response.OrderReference,
	}
	err := app.Get.Sql.SelectResult(dbPayment)
	if err != nil {
		log.Error().Err(err)
		return
	}
	changeStatusResponse, err := app.Get.Wire.GetPaymentStatus(dbPayment.Type.Name, response.OrderReference)
	if len(changeStatusResponse.Messages) == 0 {
		if err != nil {
			log.Error().Err(err)
			return
		}
		status := app.Get.GetStatusByName(response.Status)
		dbPayment.StatusId = int64(status.Id)

		err = app.Get.Sql.Update(dbPayment, "payment_number", "status_id")

		//TODO add functionality payin or payout  (select payment and search by type)
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

		err = app.Get.Sql.Update(dbPayment, "payment_number", "error")

		if err != nil {
			log.Error().Err(err)
		}
		return
	}

}
