package controllers

import (
	"crypto"
	"fmt"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/oauthRepository"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/services"
	"jwt-authentication-golang/services/auth"
	"jwt-authentication-golang/times"
	"net/http"
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

	user, message := checkUserByToken(request.TwoFaToken, request.AuthToken, request.MemberId, clientType)
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

	_, _, _, oauthCodeTime, _ := times.GetTokenTimes()

	if err := context.BindJSON(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	user, message := checkUserByToken(request.TwoFaToken, request.AuthToken, request.MemberId, clientType)
	if user == nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": message})
	}

	user.SetGoogle2FaSecret(request.Secret)
	userRepository.SaveUser(user)

	oauthClient := oauthRepository.GetOauthClientByType(clientType, constants.Personal)
	code := oauthRepository.CreateOauthCode(user.GetId(), oauthClient.Id, true, oauthCodeTime)

	if code == nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": "System error"})
		context.Abort()
		return
	}

	if services.Validate(user.GetId(), request.Code, config.Conf.App.AppName, clientType) == true {
		user.SetTwoFactorAuthSettingId(2)
		userRepository.SaveUser(user)
		context.JSON(http.StatusOK, gin.H{"data": "2fa activated"})
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

	user, message := checkUserByToken(request.TwoFaToken, request.AuthToken, request.MemberId, clientType)
	if user == nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": message})
	}
	oauthCode := oauthRepository.GetOauthCodeWithConditions(map[string]interface{}{"user_id": user.GetId(), "revoked": true})

	if oauthCode != nil && oauthCode.ExpiresAt.Unix() < newTime.Unix() {
		context.JSON(http.StatusForbidden, gin.H{"error": "Token has expired"})
		context.Abort()
		return
	}

	var key = fmt.Sprintf("%s_%d", request.Type, user.GetId())

	if twoFactorAttempt, ok := cache.Caching.TwoFactorAttempt.Get(fmt.Sprintf("%s_%d", clientType, user.GetId())); ok == true {
		cache.Caching.BlockedAccounts.Set(key, blockedTime.Unix())
		cache.Caching.TwoFactorAttempt.Set(key, twoFactorAttempt+1)
		context.JSON(http.StatusForbidden, gin.H{"error": fmt.Sprint("Account is temporary blocked for ", blockedTime.Sub(newTime))})
		context.Abort()
		return
	} else if twoFactorAttempt >= config.Conf.Jwt.MfaAttempts {
		if user.StructName() == constants.StructMember {
			user.SetIsActivated(postgres.MemberStatusSuspended)
		} else {
			user.SetIsActivated(postgres.ApplicantStateBlocked)
		}
		userRepository.SaveUser(user)
		cache.Caching.LoginAttempt.Delete(key)
		context.JSON(http.StatusForbidden, gin.H{"error": "Account is blocked. Please contact support"})
		context.Abort()
		return
	}

	if request.BackupCode != "" {
		var success = false
		backupCodeData := user.GetBackupCodeDataAttribute()
		for i, v := range backupCodeData.BackupCodes {
			if v.Code == request.BackupCode && v.Use == true {
				context.JSON(http.StatusForbidden, gin.H{"error": "This code has been already used"})
				context.Abort()
				return
			} else if v.Code == request.BackupCode {
				backupCodeData.BackupCodes[i].Use = true
				success = true
			}
		}
		user.SetBackupCodeData(backupCodeData)
		userRepository.SaveUser(user)
		if success == true {
			token, _, expirationTime, _ := services.GenerateJWT(user.GetId(), user.GetFullName(), request.Type, constants.Personal, constants.AccessToken)
			context.JSON(http.StatusOK, gin.H{"access_token": token, "token_type": "bearer", "expires_in": expirationTime.Unix()})
			return
		} else {
			context.JSON(http.StatusForbidden, gin.H{"error": "No such code"})
			context.Abort()
			return
		}
	}

	valid := services.Validate(user.GetId(), request.Code, config.Conf.App.AppName, clientType)

	if valid == false {
		twoFactorAttempt, _ := cache.Caching.TwoFactorAttempt.Get(key)
		cache.Caching.TwoFactorAttempt.Set(key, twoFactorAttempt+1)
		context.JSON(http.StatusForbidden, gin.H{"data": "Unable to verify your code"})
		context.Abort()
		return
	}

	cache.Caching.TwoFactorAttempt.Delete(key)

	token, _, _, err := services.GenerateJWT(user.GetId(), user.GetFullName(), request.Type, constants.Personal, constants.AccessToken)

	if err != nil {
		context.JSON(http.StatusForbidden, gin.H{"error": "Generate Error"})
		context.Abort()
		return
	}

	context.JSON(http.StatusOK, gin.H{"data": "success", "token": token})
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

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	user = auth.GetAuthUserByToken(constants.Personal, constants.AuthToken, request.AuthToken)
	if user == nil {
		context.JSON(http.StatusOK, gin.H{"data": "Access token not working"})
		context.Abort()
		return
	}

	if request.MemberId > 0 && request.Type == constants.Member {
		user = userRepository.GetUserById(request.MemberId, clientType)
		if user == nil {
			context.JSON(http.StatusOK, gin.H{"data": "Member not found"})
			context.Abort()
			return
		}
	}

	if services.Validate(user.GetId(), request.Code, config.Conf.App.AppName, clientType) == true {
		user.SetTwoFactorAuthSettingId(1)
		user.SetGoogle2FaSecret("")
		userRepository.SaveUser(user)
		context.JSON(http.StatusOK, gin.H{"data": "Google 2fa disabled successful"})
		context.Abort()
		return
	}
	context.JSON(http.StatusForbidden, gin.H{"data": "Unable to verify your code"})
	context.Abort()
	return
}

func checkUserByToken(twaToken string, authToken string, memberId uint64, clientType string) (user postgres.User, errorMessage string) {
	if twaToken != "" {
		user = auth.GetAuthUserByToken(constants.Personal, constants.ForTwoFactor, twaToken)
		errorMessage = "TwoFaToken not working"
	} else if authToken != "" {
		user = auth.GetAuthUserByToken(constants.Personal, constants.AuthToken, authToken)
		errorMessage = "Auth token not working"
	} else {
		errorMessage = "two factor token or Auth token required"
	}

	if user == nil {
		return
	}

	if memberId > 0 && clientType == constants.Member {
		user = userRepository.GetUserById(memberId, clientType)
		if user == nil {
			errorMessage = "Member not found"
		}
	}
	return
}
