package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	"encoding/json"
	"fmt"
	"github.com/gin-gonic/gin"
	"time"
)

func UnmarshalJson(c *gin.Context, logKey string, model models.PaymentCommon) error {
	body, err := c.GetRawData()

	if err == nil {
		logData := fmt.Sprintf("[ %v ] %s", time.Now(), string(body))

		app.Get.Log.Info().Msgf("UnmarshalJson data: %s", logData)

		app.Get.LogRedis(logKey, logData)

		err = json.Unmarshal(body, model)

		app.Get.Log.Info().Msgf("UnmarshalJson model: %#v", model)
	} else {

		app.Get.Log.Error().Err(err).Msg("json parse err")

		app.Get.LogRedis(logKey, err)
	}

	return err
}
