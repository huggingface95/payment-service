package middlewares

import (
	"fmt"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/services"

	"github.com/gin-gonic/gin"
)

func Auth() gin.HandlerFunc {
	return func(context *gin.Context) {
		var h requests.HeaderProviderRequest

		if err := context.BindHeader(&h); err != nil {
			fmt.Println("warning parent function GetAuthUserFromRequest  required PROVIDER_TYPE header")
			context.Abort()
			return
		}

		tokenString := context.GetHeader("Authorization")
		if tokenString == "" {
			context.JSON(401, gin.H{"error": "request does not contain an access token"})
			context.Abort()
			return
		}
		err := services.ValidateToken(h.ProviderType, tokenString, constants.Personal, true)
		if err != nil {
			context.JSON(401, gin.H{"error": err.Error()})
			context.Abort()
			return
		}
		context.Next()
	}
}
