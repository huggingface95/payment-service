package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/internal/redis/constants"
	"github.com/gin-gonic/gin"
)

const LogKeyPayoutRequest = "payout:request:log"

func PayoutExecution(c *gin.Context) {
	payoutRequest := &models.Payment{}

	err := UnmarshalJson(c, LogKeyPayoutRequest, payoutRequest)

	if err == nil {
		app.Get.Redis.AddList(constants.QueuePayoutLog, payoutRequest)
		return
	}

}
