package controllers

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests"
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

	user, message := auth.CheckUserByToken("", request.AccessToken, request.MemberId, clientType, context.Request.Host, context.GetHeader("test-mode"))
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

// StoreBackupCodes TODO remove this action
func StoreBackupCodes(context *gin.Context) {
	var request requests.StoreBackupCodesRequest
	var user postgres.User
	deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(context)

	if err := context.BindJSON(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		context.Abort()
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	user, message := auth.CheckUserByToken("", request.AccessToken, request.MemberId, clientType, context.Request.Host, context.GetHeader("test-mode"))
	if user == nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": message})
	}

	user.SetBackupCodeData(request.BackupCodes)
	userRepository.SaveUser(user)

	status, response := auth.GetLoginResponse(user, constants.Personal, constants.AccessToken, deviceInfo, context.GetHeader("test-mode"))

	if status == http.StatusOK {
		response["data"] = fmt.Sprintf("Backup Codes stored success for user id %d", +user.GetId())
	}
	context.JSON(status, response)
	return
}
