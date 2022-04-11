package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/redis/constants"
	"cl-junc-api/pkg/utils/log"
	"github.com/gin-gonic/gin"
)

const LogKeyPayoutRequest = "payout:request:log"

func PayoutExecution(c *gin.Context) {
	payoutRequest := &models.Payment{}

	err := UnmarshalJson(c, LogKeyPayoutRequest, payoutRequest)

	if err != nil {
		log.Error().Err(err)
		return
	}
	app.Get.Redis.AddList(constants.QueuePayoutLog, payoutRequest)
	return

}
