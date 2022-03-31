package api

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/clearjunction/models"
	"encoding/json"
	"fmt"
	"github.com/gin-gonic/gin"
	"log"
	"time"
)

func UnmarshalJson(c *gin.Context, logKey string, model models.PaymentCommon) error {
	body, err := c.GetRawData()

	if err == nil {

		logData := fmt.Sprintf("[ %v ] %s", time.Now(), string(body))
		log.Println(logData)
		app.Get.LogRedis(logKey, logData)

		err = json.Unmarshal(body, model)
	} else {
		log.Println("json parse", err)
		app.Get.LogRedis(logKey, err)
	}

	return err
}
