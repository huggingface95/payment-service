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
	r.POST("/clearjunction/iban-compnay/check", api.IbanCompanyCheck)
	r.POST("/clearjunction/postback", api.CljPostback)
	r.POST("/clearjunction/iban/postback", api.IbanPostback)
	r.Run(":8080")
}

func index(c *gin.Context) {
	c.AbortWithStatusJSON(500, gin.H{
		"code":    500,
		"message": "?",
	})
}
