package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	"cl-junc-api/pkg/utils/log"
	"encoding/json"
	"fmt"
	"github.com/gin-gonic/gin"
	"time"
)

func UnmarshalJson(c *gin.Context, logKey string, model models.PaymentCommon) error {
	body, err := c.GetRawData()

	if err == nil {
		logData := fmt.Sprintf("[ %v ] %s", time.Now(), string(body))

		log.Info().Msgf("UnmarshalJson data: %s", logData)

		app.Get.LogRedis(logKey, logData)

		err = json.Unmarshal(body, model)

		if err == nil {
			log.Error().Err(err)
		}

		log.Info().Msgf("UnmarshalJson model: %#v", model)
	} else {

		log.Error().Err(err).Msg("json parse err")

		app.Get.LogRedis(logKey, err)
	}

	return err
}
