package controllers

import "C"
import (
	"fmt"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/oauthRepository"
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
	var key = fmt.Sprintf("%s_%d", user.ClientType(), user.GetId())

	token := context.GetHeader("Authorization")

	newToken, expirationTime, err := services.GenerateJWT(user.GetId(), user.GetFullName(), user.GetModelType(), constants.Personal, constants.AccessToken, context.Request.Host)

	if err != nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": err})
		context.Abort()
		return
	}

	cache.Caching.BlackList.Set(key, &cache.BlackListData{Token: token, Forever: false})

	context.JSON(http.StatusOK, gin.H{"access_token": newToken, "token_type": "bearer", "expires_in": expirationTime.Unix()})

}

func Generate2faToken(context *gin.Context) {
	var user postgres.User
	var bearerJWT requests.BearerJWT
	deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(context)

	if err := context.BindHeader(&bearerJWT); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	user = auth.GetAuthUserByToken(constants.Personal, constants.AccessToken, bearerJWT.Bearer, context.Request.Host)

	if user == nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": "User not found"})
		return
	}

	if user.GetTwoFactorAuthSettingId() == 2 {
		if user.IsGoogle2FaSecret() == false {
			tokenJWT, _, err := services.GenerateJWT(user.GetId(), user.GetFullName(), user.ClientType(), constants.Personal, constants.ForTwoFactor, deviceInfo.Host)
			if err != nil {
				oauthRepository.InsertAuthLog(user.ClientType(), user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
				context.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
				return
			}
			context.JSON(http.StatusOK, gin.H{"2fa_token": tokenJWT})
			return
		} else {
			context.JSON(http.StatusUnauthorized, gin.H{"error": "Google secret exists"})
		}

	}

	context.JSON(http.StatusUnauthorized, gin.H{"error": "Auth settings id must be 2"})
	return
}
