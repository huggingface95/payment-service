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
	r.GET("/clearjunction/iban-company/check", api.IbanCompanyCheck)
	r.POST("/clearjunction/postback", api.CljPostback)
	r.POST("/clearjunction/iban/postback", api.IbanPostback)
	err := r.Run(":2490")
	if err != nil {
		return
	}
}

func index(c *gin.Context) {
	c.AbortWithStatusJSON(500, gin.H{
		"code":    500,
		"message": "?",
	})
}
