package controllers

import "C"
import (
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/services"
	"jwt-authentication-golang/services/auth"
	"net/http"
)

func Refresh(context *gin.Context) {
	var request requests.RefreshRequest
	if err := context.BindJSON(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		context.Abort()
		return
	}

	user := auth.GetAuthUserFromRequest(context)
	if user == nil {
		context.JSON(http.StatusForbidden, gin.H{"error": "Not found bearer token"})
		context.Abort()
		return
	}

	token := context.GetHeader("Authorization")

	newToken, _, expirationTime, err := services.GenerateJWT(user.GetId(), user.GetTwoFactorAuthSettingId(), user.GetFullName(), user.GetModelType(), constants.Personal)

	if err != nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": err})
		context.Abort()
		return
	}

	cache.Caching.BlackList.Set(&cache.BlackListData{
		Id:      user.GetId(),
		Token:   token,
		Forever: false,
	})

	context.JSON(http.StatusOK, gin.H{"access_token": newToken, "token_type": "bearer", "expires_in": expirationTime.Unix()})

}
