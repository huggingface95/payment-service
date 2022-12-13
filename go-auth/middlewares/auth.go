package middlewares

import (
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/services"

	"github.com/gin-gonic/gin"
)

func Auth() gin.HandlerFunc {
	return func(context *gin.Context) {
		tokenString := context.GetHeader("Authorization")
		if tokenString == "" {
			context.JSON(401, gin.H{"error": "request does not contain an access token"})
			context.Abort()
			return
		}
		errAccessToken := services.ValidateAccessToken(tokenString, constants.Personal, true)
		errAuthToken := services.ValidateAuthToken(tokenString, constants.Personal, true)

		if errAuthToken == nil || errAccessToken == nil {
			context.Next()
			return
		}

		if errAuthToken != nil {
			context.JSON(401, gin.H{"error": errAuthToken})
		} else if errAccessToken != nil {
			context.JSON(401, gin.H{"error": errAccessToken})
		}
		context.Abort()
		return
	}
}
