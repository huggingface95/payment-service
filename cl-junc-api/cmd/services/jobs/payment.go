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
		Id:            response.CustomFormat.PaymentId,
		PaymentNumber: response.OrderReference,
	}

	if len(response.Messages) == 0 {
		dbPayment.Status.Name = response.Status

		err := app.Get.Sql.Update(dbPayment, "id", "payment_number")

		//TODO add functionality payin or payout  (select payment and search by type)
		if err == nil {
			email := &models.Email{
				Id:      response.CustomFormat.PaymentId,
				Type:    "payin",
				Status:  response.Status,
				Message: "Payin Success",
				Data:    response,
				Details: map[string]string{"test": "", "info": ""},
				Error:   response.Messages,
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

		err = app.Get.Sql.Update(dbPayment, "id", "payment_number")

		if err != nil {
			log.Error().Err(err)
		}
		return
	}

}
