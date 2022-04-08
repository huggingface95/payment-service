package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/redis/constants"
	"cl-junc-api/internal/redis/models"
	"github.com/gin-gonic/gin"
)

const LogKeyPayInRequest = "payin:request:log"

func PayInCreateInvoice(c *gin.Context) {

	payInRequest := &models.Payment{}

	err := UnmarshalJson(c, LogKeyPayInRequest, payInRequest)

	if err == nil {
		app.Get.Redis.AddList(constants.QueuePayInLog, payInRequest)
		return
	}

}
