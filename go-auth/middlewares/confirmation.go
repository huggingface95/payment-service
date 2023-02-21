package middlewares

import (
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"net/http"
)

func CheckIpConfirmation() gin.HandlerFunc {
	return func(context *gin.Context) {
		token := context.Request.URL.Query().Get("token")
		if token == "" {
			context.JSON(http.StatusBadRequest, gin.H{"error": "request not working"})
			context.Abort()
			return
		}

		if data := cache.Caching.ConfirmationIpLinks.Get(token); data != nil {
			context.Next()
		} else {
			context.JSON(http.StatusForbidden, gin.H{"error": "token don't working"})
			context.Abort()
			return
		}

	}
}

func CheckIndividualEmailConfirmation() gin.HandlerFunc {
	return func(context *gin.Context) {
		token := context.Request.URL.Query().Get("token")
		if token == "" {
			context.JSON(http.StatusBadRequest, gin.H{"error": "request not working"})
			context.Abort()
			return
		}

		if data := cache.Caching.ConfirmationEmailLinks.Get(token); data != nil {
			context.Next()
		} else {
			context.JSON(http.StatusForbidden, gin.H{"error": "token don't working"})
			context.Abort()
			return
		}

	}
}
