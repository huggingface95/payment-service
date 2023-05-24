package middlewares

import (
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/services"
	"strings"

	"github.com/gin-gonic/gin"
)

func AccessAuth() gin.HandlerFunc {
	return func(context *gin.Context) {
		tokenString := context.GetHeader("Authorization")
		if tokenString == "" {
			context.JSON(401, gin.H{"error": "request does not contain an access token"})
			context.Abort()
			return
		}
		errAccessToken := services.ValidateAccessToken(tokenString, constants.Personal)

		if errAccessToken == nil {
			context.Set("bearer", strings.Replace(tokenString, "Bearer ", "", -1))
			context.Next()
			return
		}

		context.JSON(401, gin.H{"error": errAccessToken.Error()})
		context.Abort()
		return
	}
}
