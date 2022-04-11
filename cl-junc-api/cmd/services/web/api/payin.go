package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/redis/constants"
	"cl-junc-api/internal/redis/models"
	"cl-junc-api/pkg/utils/log"
	"github.com/gin-gonic/gin"
)

const LogKeyPayInRequest = "payin:request:log"

func PayInCreateInvoice(c *gin.Context) {
	payInRequest := &models.Payment{}

	log.Debug().Msgf("payment: PayInCreateInvoice: request: %#v", payInRequest)

	err := UnmarshalJson(c, LogKeyPayInRequest, payInRequest)

	if err != nil {
		log.Error().Err(err)
		return
	}
	app.Get.Redis.AddList(constants.QueuePayInLog, payInRequest)
	return

}
