package controllers

import (
	"encoding/json"
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

	if request.AuthToken != "" {

	}

	if request.AccessToken != "" {
		user = auth.GetAuthUserFromRequest(context)
	}

	if request.MemberId > 0 {
		user = userRepository.GetUserById(request.MemberId, request.Type)
	}

	if user == nil {
		user = auth.GetAuthUserFromRequest(context)
	}

	if user == nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": "User not found"})
		return
	}

	codes := make([]string, 9)

	for k := range codes {
		codes[k] = helpers.GenerateRandomString(3)
	}

	jsonCodes, err := json.Marshal(codes)
	if err != nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}

	context.JSON(http.StatusOK, gin.H{"backup_codes": jsonCodes, "user_id": user.GetId(), "2fa_secret": user.GetGoogle2FaSecret()})
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

	if request.AuthToken != "" {

	}

	if request.AccessToken != "" {
		user = auth.GetAuthUserFromRequest(context)
		if user == nil {
			context.JSON(http.StatusForbidden, gin.H{"data": "Member not found"})
			context.Abort()
			return
		}
	}

	if request.MemberId > 0 {
		user = userRepository.GetUserById(request.MemberId, request.Type)
		if user == nil {
			context.JSON(http.StatusForbidden, gin.H{"data": "Member not found"})
			context.Abort()
			return
		}
	}

	if user == nil {
		user = auth.GetAuthUserFromRequest(context)
	}

	user.SetBackupCodeData(request.BackupCodes)
	userRepository.SaveUser(user)

	token, _, _, err := services.GenerateJWT(user.GetId(), user.GetFullName(), request.Type, constants.Personal, constants.AccessToken)
	if err != nil {
		context.JSON(http.StatusForbidden, gin.H{"error": "Token don't generate"})
		context.Abort()
		return
	}
	context.JSON(http.StatusOK, gin.H{
		"data":         fmt.Sprintf("Backup Codes stored success for user id %d", +user.GetId()),
		"access_token": token,
	})
	context.Abort()
	return
}
