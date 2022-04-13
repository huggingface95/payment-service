package jobs

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	"cl-junc-api/internal/redis/models"
	"cl-junc-api/pkg/utils/log"
)

func ProcessPayQueue() {

	payInList := getPayments(constants.QueuePayInLog)
	payoutList := getPayments(constants.QueuePayoutLog)

	for _, p := range payInList {
		log.Debug().Msgf("jobs: ProcessPayQueue: p: %#v", p)
		payIn(p)
	}

	for _, p := range payoutList {
		payout(p)
	}
}

func getPayments(logType string) []*models.Payment {
	redisList := app.Get.GetRedisList(logType, func() interface{} {
		return new(models.Payment)
	})

	log.Debug().Msgf("jobs: getPayments: redisList: %#v", redisList)

	var newList []*models.Payment

	for _, c := range redisList {
		newList = append(newList, c.(*models.Payment))
	}

	log.Debug().Msgf("jobs: getPayments: newList: %#v", newList)

	return newList
}

func payIn(p *models.Payment) {
	//TODO add this params to payIn struct
	//request := &models.PayInInvoiceRequest{
	//	PostbackUrl: app.Get.Config().App.Url + "/payin/postback",
	//	SuccessUrl:  app.Get.Config().App.Url + "/payin/postback",
	//	FailUrl:     app.Get.Config().App.Url + "/payin/postback",
	//}

	dbPayment := &db.Payment{
		Id: p.PaymentId,
	}

	err := app.Get.Sql.SelectResult(dbPayment)

	payInRequest := dbPayment.ToPayInRequest()

	if err == nil {
		response, err := app.Get.Wire.CreateInvoice(payInRequest)
		if err == nil {
			dbPayment.PaymentNumber = response.OrderReference

			err = app.Get.Sql.Update(dbPayment, "payment_number")

			if err == nil {
				email := &models.Email{
					Id:      p.PaymentId,
					Type:    "payin",
					Status:  response.Status,
					Message: "Payin Success",
					Data:    response,
					Details: map[string]string{"test": "", "info": ""},
					Error:   response.Messages,
				}

				app.Get.Redis.AddList(constants.QueueEmailLog, email)

			}
		}
	} else {
		log.Error().Err(err)
	}
}

func payout(p *models.Payment) {
	//TODO add parameter to payment method
	//request := &models.PayoutExecutionRequest{
	//	PostbackUrl: app.Get.Config().App.Url + "/payout/postback",
	//}

	payment := &db.Payment{
		Id: p.PaymentId,
	}
	payout := &db.Payout{}

	err := app.Get.Sql.SelectOne(payment, payout, "id")

	if err == nil {
		response, err := app.Get.Wire.CreateExecution(payout)

		if err == nil {
			payment := &db.Payment{
				Id:            payment.Id,
				PaymentNumber: response.OrderReference,
			}
			err = app.Get.Sql.Update(payment, "payment_number")

			if err == nil {
				email := &models.Email{
					Type:    "payout",
					Status:  response.Status,
					Message: "Payout Send",
					Data:    response,
					Details: map[string]string{"test": "", "info": ""},
					Error:   response.Messages,
				}
				app.Get.Redis.AddList(constants.QueueEmailLog, email)
			}

		}

	}
}
