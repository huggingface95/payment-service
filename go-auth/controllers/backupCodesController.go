package controllers

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/services"
	"jwt-authentication-golang/services/auth"
	"net/http"
)

func GenerateBackupCodes(context *gin.Context) {
	var request requests.GenerateBackupCodesRequest
	var user postgres.User

	if err := context.BindJSON(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		context.Abort()
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	user, message := auth.CheckUserByToken("", request.AccessToken, request.MemberId, clientType)
	if user == nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": message})
	}

	codes := make([]string, 9)

	for k := range codes {
		codes[k] = helpers.GenerateRandomString(3)
	}

	context.JSON(http.StatusOK, gin.H{"backup_codes": codes, "user_id": user.GetId(), "2fa_secret": user.GetGoogle2FaSecret()})
	return

}

func StoreBackupCodes(context *gin.Context) {
	var request requests.StoreBackupCodesRequest
	var user postgres.User

	if err := context.BindJSON(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		context.Abort()
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	user, message := auth.CheckUserByToken("", request.AccessToken, request.MemberId, clientType)
	if user == nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": message})
	}

	user.SetBackupCodeData(request.BackupCodes)
	userRepository.SaveUser(user)

	token, _, expirationTime, err := services.GenerateJWT(user.GetId(), user.GetFullName(), clientType, constants.Personal, constants.AccessToken)
	if err != nil {
		context.JSON(http.StatusForbidden, gin.H{"error": "Token don't generate"})
		context.Abort()
		return
	}
	context.JSON(http.StatusOK, gin.H{
		"access_token": token,
		"token_type":   "bearer",
		"expires_in":   expirationTime.Unix(),
		"data":         fmt.Sprintf("Backup Codes stored success for user id %d", +user.GetId()),
	})
	context.Abort()
	return
}
