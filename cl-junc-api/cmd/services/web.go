package services

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/cmd/services/web/api"
	"github.com/gin-gonic/gin"
)

func Web() {
	if app.Get.Config().App.IsProduction {
		gin.SetMode(gin.ReleaseMode)
	}

	r := gin.Default()

	r.GET("/", index)
	r.POST("/payin/invoice", api.PayinCreateInvoice)
	r.POST("/payout/execution", api.PayoutExecution)
	r.POST("/payin/postback", api.PayinPostBack)
	r.POST("/payout/postback", api.PayoutPostBack)

	r.Run(":8080")
}

func index(c *gin.Context) {
	c.AbortWithStatusJSON(500, gin.H{
		"code":    500,
		"message": "?",
	})
}
