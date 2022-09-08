package jobs

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	models2 "cl-junc-api/internal/redis/models"
	"cl-junc-api/pkg/utils/log"
)

func ProcessIbanIndGenerateQueue() {
	for {
		redisData := app.Get.GetRedisDataByBlPop(constants.QueueIbanIndividualLog, func() interface{} {
			return new(models2.IbanRequest)
		})
		if redisData == nil {
			break
		}
		createIndividualIban(redisData.(*models2.IbanRequest))
	}
}

func createIndividualIban(request *models2.IbanRequest) {

	dbAccount := app.Get.GetAccountWithRelations(&db.Account{Id: request.AccountId}, []string{"State", "Provider", "Payee"}, "id")
	if dbAccount.Provider.Name == db.CLEARJUNCTION {
		response := app.Get.Wire.Iban(dbAccount)
		if response == nil {
			return
		}
		statusResponse, err := app.Get.Wire.GetIbanStatus(response.OrderReference)
		if err != nil {
			log.Error().Err(err)
			return
		}
		dbAccount.AccountState = db.GetAccountState(statusResponse.Status)
		dbAccount.OrderReference = statusResponse.OrderReference

		if len(statusResponse.Messages) == 0 {
			app.Get.UpdateAccount(dbAccount, "id", "account_state_id", "order_reference")
		}
	}

	return
}
