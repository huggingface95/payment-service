package jobs

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	models2 "cl-junc-api/internal/redis/models"
	"cl-junc-api/pkg/utils/log"
)

func ProcessIbanQueue() {
	for {
		redisData := app.Get.GetRedisDataByBlPop(constants.QueueIbanLog, func() interface{} {
			return new(models2.IbanRequest)
		})
		if redisData == nil {
			break
		}
		createIban(redisData.(*models2.IbanRequest))
	}
}

func createIban(request *models2.IbanRequest) {
	dbAccount := app.Get.GetAccountWithRelations(&db.Account{Id: request.AccountId}, []string{"AccountState"}, "id")
	response := app.Get.Wire.Iban(dbAccount)
	if response == nil {
		return
	}
	statusResponse, err := app.Get.Wire.GetIbanStatus(response.OrderReference)
	if err != nil {
		log.Error().Err(err)
		return
	}

	if len(statusResponse.Messages) == 0 {
		state := app.Get.GetAccountStateByName(statusResponse.Status)
		dbAccount.StateId = int64(state.Id)
		app.Get.UpdateAccount(dbAccount, "id", "account_state")
	}

	return
}
