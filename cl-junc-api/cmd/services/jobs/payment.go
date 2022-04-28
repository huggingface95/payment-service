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

	payInList := getPayInPayments()
	payoutList := getPayOutPayments()

	for _, p := range payInList {
		log.Debug().Msgf("jobs: ProcessPayQueue: p: %#v", p)
		payIn(p)
	}

	for _, p := range payoutList {
		payout(p)
	}
}

func getPayInPayments() []*models2.PayInInvoiceResponse {
	redisList := app.Get.GetRedisList(constants.QueuePayInLog, func() interface{} {
		return new(models2.PayInInvoiceResponse)
	})

	log.Debug().Msgf("jobs: getPayments: redisList: %#v", redisList)

	var newList []*models2.PayInInvoiceResponse

	for _, c := range redisList {
		newList = append(newList, c.(*models2.PayInInvoiceResponse))
	}

	log.Debug().Msgf("jobs: getPayInPayments: newList: %#v", newList)

	return newList
}

func getPayOutPayments() []*models2.PayoutExecutionResponse {
	redisList := app.Get.GetRedisList(constants.QueuePayoutLog, func() interface{} {
		return new(models2.PayoutExecutionResponse)
	})

	log.Debug().Msgf("jobs: getPayments: redisList: %#v", redisList)

	var newList []*models2.PayoutExecutionResponse

	for _, c := range redisList {
		newList = append(newList, c.(*models2.PayoutExecutionResponse))
	}

	log.Debug().Msgf("jobs: getPayInPayments: newList: %#v", newList)

	return newList
}

func payIn(response *models2.PayInInvoiceResponse) {

	dbPayment := &db.Payment{
		Id:            response.CustomFormat.PaymentId,
		PaymentNumber: response.OrderReference,
	}

	if len(response.Messages) == 0 {
		dbPayment.Status.Name = response.Status

		err := app.Get.Sql.Update(dbPayment, "id", "payment_number")

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

func payout(response *models2.PayoutExecutionResponse) {

	dbPayment := &db.Payment{
		Id:            response.CustomFormat.PaymentId,
		PaymentNumber: response.OrderReference,
	}

	if len(response.Messages) == 0 {
		dbPayment.Status.Name = response.Status

		err := app.Get.Sql.Update(dbPayment, "id", "payment_number")

		if err == nil {
			email := &models.Email{
				Id:      response.CustomFormat.PaymentId,
				Type:    "payout",
				Status:  response.Status,
				Message: "Payout Success",
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
