package controllers

import (
	"crypto"
	"fmt"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/oauthRepository"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/services"
	"jwt-authentication-golang/services/auth"
	"jwt-authentication-golang/times"
	"net/http"
	"strconv"
)

func GenerateTwoFactorQr(context *gin.Context) {
	var request requests.GenerateTwoFactorQrRequest
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

	user, message := auth.CheckUserByToken(request.TwoFaToken, request.AccessToken, request.MemberId, clientType, context.Request.Host, context.GetHeader("test-mode"))
	if user == nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": message})
	}

	qr, code, err := services.GenerateTwoFactorQr(clientType, user.GetId(), user.GetEmail(), config.Conf.App.AppName, crypto.SHA1, 6)
	if err != nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": "Don't generate two factor token"})
		context.Abort()
		return
	}

	context.JSON(http.StatusOK, gin.H{"image": qr, "code": code})
}

func ActivateTwoFactorQr(context *gin.Context) {
	var request requests.ActivateTwoFactorQrRequest
	var user postgres.User

	if err := context.BindJSON(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	user, message := auth.CheckUserByToken(request.TwoFaToken, request.AccessToken, request.MemberId, clientType, context.Request.Host, context.GetHeader("test-mode"))
	if user == nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": message})
	}

	user.SetGoogle2FaSecret(request.Secret)
	userRepository.SaveUser(user)

	if services.Validate(user.GetId(), request.Code, config.Conf.App.AppName, clientType) == true {
		user.SetTwoFactorAuthSettingId(2)
		userRepository.SaveUser(user)
		codes := make([]postgres.BackupCodes, 9)
		for k := range codes {
			codes[k] = postgres.BackupCodes{Code: helpers.GenerateRandomInteger(100000, 999999), Use: false}
		}
		context.JSON(http.StatusOK, gin.H{"message": "2fa activated", "data": codes})
		context.Abort()
		return
	} else {
		context.JSON(http.StatusForbidden, gin.H{"data": "Not working secret"})
		context.Abort()
		return
	}

}

func VerifyTwoFactorQr(context *gin.Context) {
	var request requests.VerifyTwoFactorQrRequest
	var user postgres.User

	deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(context)

	newTime, blockedTime, _, _, _ := times.GetTokenTimes()

	if err := context.BindJSON(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		context.Abort()
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	user, message := auth.CheckUserByToken(request.TwoFaToken, request.AccessToken, request.MemberId, clientType, context.Request.Host, context.GetHeader("test-mode"))
	if user == nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": message})
	}

	var key = fmt.Sprintf("%s_%d", request.Type, user.GetId())

	if twoFactorAttempt := cache.Caching.TwoFactorAttempt.GetAttempt(fmt.Sprintf("%s_%d", clientType, user.GetId()), false); twoFactorAttempt == config.Conf.Jwt.MfaAttempts {
		cache.Caching.BlockedAccounts.Set(key, &blockedTime)
		cache.Caching.TwoFactorAttempt.Set(key, twoFactorAttempt+1)
		context.JSON(http.StatusForbidden, gin.H{"error": fmt.Sprint("Account is temporary blocked for ", blockedTime.Sub(newTime))})
		context.Abort()
		return
	} else if twoFactorAttempt >= ((config.Conf.Jwt.MfaAttempts * 2) + 1) {
		if user.StructName() == constants.StructMember {
			user.SetIsActivated(postgres.MemberStatusSuspended)
		} else {
			user.SetIsActivated(postgres.ApplicantStateBlocked)
		}
		userRepository.SaveUser(user)
		cache.Caching.LoginAttempt.Del(key)
		context.JSON(http.StatusForbidden, gin.H{"error": "Account is blocked. Please contact support"})
		context.Abort()
		return
	}

	if request.BackupCodes != nil {
		user.SetBackupCodeData(request.BackupCodes)
		userRepository.SaveUser(user)
	}

	if services.Validate(user.GetId(), request.Code, config.Conf.App.AppName, clientType) == false {
		var success = false
		backupCodes := user.GetBackupCodeDataAttribute()
		for i, v := range backupCodes {
			if strconv.Itoa(v.Code) == request.Code && v.Use == false {
				backupCodes[i].Use = true
				user.SetBackupCodeData(backupCodes)
				userRepository.SaveUser(user)
				success = true
				break
			}
		}
		if success == false {
			twoFactorAttempt := cache.Caching.TwoFactorAttempt.GetAttempt(key, false)
			cache.Caching.TwoFactorAttempt.Set(key, twoFactorAttempt+1)
			context.JSON(http.StatusForbidden, gin.H{"data": "Unable to verify your code"})
			context.Abort()
			return
		}
	}

	cache.Caching.TwoFactorAttempt.Del(key)

	token, expirationTime, err := services.GenerateJWT(user.GetId(), user.GetFullName(), clientType, constants.Personal, constants.AccessToken, context.Request.Host, context.GetHeader("test-mode"))

	if err != nil {
		context.JSON(http.StatusForbidden, gin.H{"error": "Generate Error"})
		context.Abort()
		return
	}

	oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
	oauthRepository.InsertActiveSessionLog(clientType, user.GetEmail(), user.GetCompany().Name, true, true, &expirationTime, deviceInfo)

	context.JSON(http.StatusOK, gin.H{"access_token": token, "token_type": "bearer", "expires_in": expirationTime.Unix()})
	context.Abort()
	return
}

func DisableTwoFactorQr(context *gin.Context) {
	var request requests.DisableTwoFactorQrRequest
	var user postgres.User
	if err := context.BindJSON(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		context.Abort()
		return
	}

	if request.Code == "" && request.MemberId == 0 {
		context.JSON(http.StatusOK, gin.H{"error": "Code or member id field required"})
		context.Abort()
		return
	}

	user = auth.GetAuthUserByToken(constants.Personal, constants.AccessToken, context.GetString("bearer"), context.Request.Host, context.GetHeader("test-mode"))
	if user == nil {
		context.JSON(http.StatusOK, gin.H{"error": "User not found"})
		context.Abort()
		return
	}

	if request.MemberId > 0 && user.ClientType() == constants.Member {
		user = userRepository.GetUserById(request.MemberId, user.ClientType())
		if user == nil {
			context.JSON(http.StatusOK, gin.H{"data": "Member not found"})
			context.Abort()
			return
		}
	}

	if request.MemberId == 0 {
		if services.Validate(user.GetId(), request.Code, config.Conf.App.AppName, user.ClientType()) == false {
			context.JSON(http.StatusForbidden, gin.H{"data": "Unable to verify your code"})
			context.Abort()
			return
		}
	}

	user.SetTwoFactorAuthSettingId(1)
	user.SetGoogle2FaSecret("")
	userRepository.SaveUser(user)
	context.JSON(http.StatusOK, gin.H{"data": "Google 2fa disabled successful"})
	context.Abort()
	return

}
