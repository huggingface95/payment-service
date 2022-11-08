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

	if err := context.ShouldBind(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		context.Abort()
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	if request.TwoFaToken != "" {
		accessToken := oauthRepository.GetOauthAccessTokenWithConditions(map[string]interface{}{"id": request.TwoFaToken})
		if accessToken.GetUser() == nil {
			context.JSON(http.StatusOK, gin.H{"data": "Member not found"})
			context.Abort()
			return
		}
		user = accessToken.GetUser()
	} else if request.AccessToken != "" {
		user = auth.GetAuthUserFromRequest(context, clientType)
		if user == nil {
			context.JSON(http.StatusOK, gin.H{"data": "Member not found"})
			context.Abort()
			return
		}

	} else if request.MemberId > 0 {
		user = userRepository.GetUserById(request.MemberId, clientType)

		if user == nil {
			context.JSON(http.StatusOK, gin.H{"data": "Member not found"})
			context.Abort()
			return
		}
	} else {
		context.JSON(http.StatusBadRequest, gin.H{"error": "Bad request"})
		context.Abort()
		return
	}

	qr, code, err := services.GenerateTwoFactorQr(user.GetId(), user.GetEmail(), config.Conf.App.AppName, crypto.SHA1, 8)
	if err != nil {
		context.JSON(http.StatusOK, gin.H{"data": "Don't generate two factor token"})
		context.Abort()
		return
	}

	context.JSON(http.StatusOK, gin.H{"image": qr, "code": code})
}

func VerifyTwoFactorQr(context *gin.Context) {
	var request requests.VerifyTwoFactorQrRequest
	var user postgres.User

	newTime, blockedTime, _, _, _ := times.GetTokenTimes()

	if err := context.Bind(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		context.Abort()
		return
	}

	if request.AuthToken != "" {
		accessToken := oauthRepository.GetOauthAccessTokenWithConditions(map[string]interface{}{"id": request.AuthToken})
		if accessToken.GetUser() == nil {
			context.JSON(http.StatusOK, gin.H{"data": "Member not found"})
			context.Abort()
			return
		}
		user = accessToken.GetUser()
	}

	if request.MemberId > 0 {
		user = userRepository.GetUserById(request.MemberId, request.Type)
		if user == nil {
			context.JSON(http.StatusOK, gin.H{"data": "Member not found"})
			context.Abort()
			return
		}
	}

	oauthCode := oauthRepository.GetOauthCodeWithConditions(map[string]interface{}{"user_id": user.GetId()})

	if oauthCode.ExpiresAt.Unix() < newTime.Unix() {
		context.JSON(http.StatusForbidden, gin.H{"error": "Token has expired"})
		context.Abort()
		return
	}

	var key = fmt.Sprintf("%s_%d", request.Type, user.GetId())

	if twoFactorAttempt, ok := cache.Caching.TwoFactorAttempt.Get(fmt.Sprintf("%s_%d", "members", user.GetId())); ok == true {
		cache.Caching.BlockedAccounts.Set(key, blockedTime.Unix())
		cache.Caching.TwoFactorAttempt.Set(key, twoFactorAttempt+1)
		if authUserData, ok := cache.Caching.AuthUser.Get(key); ok == true {
			cache.Caching.BlackList.Set(&cache.BlackListData{
				Forever: false,
				Token:   authUserData.Token,
				Id:      user.GetId(),
			})
		}
		context.JSON(http.StatusForbidden, gin.H{"error": fmt.Sprint("Account is temporary blocked for ", blockedTime.Sub(newTime))})
		context.Abort()
		return
	} else if twoFactorAttempt >= config.Conf.Jwt.MfaAttempts {
		user.SetIsActivated(false)
		userRepository.SaveUser(user)
		cache.Caching.LoginAttempt.Delete(key)
		if authUserData, ok := cache.Caching.AuthUser.Get(key); ok == true {
			cache.Caching.BlackList.Set(&cache.BlackListData{
				Forever: false,
				Token:   authUserData.Token,
				Id:      user.GetId(),
			})
		}
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
			token, _, _, _ := services.GenerateJWT(user.GetId(), user.GetTwoFactorAuthSettingId(), user.GetFullName(), request.Type, constants.Personal)
			context.JSON(http.StatusOK, gin.H{"data": "success", "token": token})
			return
		} else {
			context.JSON(http.StatusForbidden, gin.H{"error": "No such code"})
			context.Abort()
			return
		}
	}

	accessToken := oauthRepository.GetOauthAccessTokenWithConditions(map[string]interface{}{"user_id": user.GetId()})

	valid := services.Validate(user.GetId(), request.Code, config.Conf.App.AppName)

	if valid == false {
		accessToken.TwoFactorVerified = false
		oauthRepository.SaveOauthAccessToken(accessToken)
		twoFactorAttempt, _ := cache.Caching.TwoFactorAttempt.Get(key)
		cache.Caching.TwoFactorAttempt.Set(key, twoFactorAttempt+1)
		context.JSON(http.StatusForbidden, gin.H{"data": "Unable to verify your code"})
		context.Abort()
		return
	}
	accessToken.TwoFactorVerified = true
	oauthRepository.SaveOauthAccessToken(accessToken)

	cache.Caching.TwoFactorAttempt.Delete(key)

	token, _, _, err := services.GenerateJWT(user.GetId(), user.GetTwoFactorAuthSettingId(), user.GetFullName(), request.Type, constants.Personal)

	if err != nil {
		context.JSON(http.StatusForbidden, gin.H{"error": "Generate Error"})
		context.Abort()
		return
	}

	context.JSON(http.StatusOK, gin.H{"data": "success", "token": token})
	context.Abort()
	return
}

func ActivateTwoFactorQr(context *gin.Context) {
	var request requests.ActivateTwoFactorQrRequest
	var user postgres.User

	_, _, _, oauthCodeTime, _ := times.GetTokenTimes()

	if err := context.Bind(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	if request.AccessToken != "" {
		claims := services.GetClaims(request.Type, request.AccessToken, constants.GrantPassword, false)
		if claims == nil {
			context.JSON(http.StatusOK, gin.H{"data": "Member not found"})
			return
		}
		user = userRepository.GetUserById(claims.GetSubject(), request.Type)
	}

	if request.AuthToken != "" {
		accessToken := oauthRepository.GetOauthAccessTokenWithConditions(map[string]interface{}{"id": request.AuthToken})
		if accessToken.GetUser() == nil {
			context.JSON(http.StatusOK, gin.H{"data": "Member not found"})
			context.Abort()
			return
		}
		user = accessToken.GetUser()
	}

	if request.MemberId > 0 {
		user = userRepository.GetUserById(request.MemberId, request.Type)
		if user == nil {
			context.JSON(http.StatusOK, gin.H{"data": "Member not found"})
			return
		}
	}
	user.SetGoogle2FaSecret(request.Secret)
	userRepository.SaveUser(user)

	oauthRepository.CreateOauthCode(user.GetId(), 1, true, oauthCodeTime)

	_, _, _, err := services.GenerateJWT(user.GetId(), user.GetTwoFactorAuthSettingId(), user.GetFullName(), request.Type, constants.GrantPassword)
	if err != nil {
		return
	}

	if services.Validate(user.GetId(), request.Code, config.Conf.App.AppName) == false {
		user.SetTwoFactorAuthSettingId(2)
		userRepository.SaveUser(user)
		context.JSON(http.StatusOK, gin.H{"data": "2fa activated"})
		context.Abort()
		return
	} else {
		context.JSON(http.StatusForbidden, gin.H{"data": "Unable to verify your code"})
		context.Abort()
		return
	}

}

func DisableTwoFactorQr(context *gin.Context) {
	var request requests.DisableTwoFactorQrRequest
	var user postgres.User
	if err := context.Bind(&request); err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		context.Abort()
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	user = auth.GetAuthUserFromRequest(context, clientType)
	credentialError := user.CheckPassword(request.Password)
	if credentialError != nil {
		context.JSON(http.StatusUnauthorized, gin.H{"error": "Password is not valid"})
		context.Abort()
		return
	}

	oauthToken := oauthRepository.GetOauthAccessTokenWithConditions(map[string]interface{}{"user_id": user.GetId()})

	if services.Validate(user.GetId(), request.Code, config.Conf.App.AppName) == false {
		user.SetTwoFactorAuthSettingId(1)
		user.SetGoogle2FaSecret("")
		userRepository.SaveUser(user)
		oauthToken.TwoFactorVerified = false
		oauthRepository.SaveOauthAccessToken(oauthToken)
		context.JSON(http.StatusOK, gin.H{"data": "Google 2fa disabled successful"})
		context.Abort()
		return
	}
	context.JSON(http.StatusForbidden, gin.H{"data": "Unable to verify your code"})
	context.Abort()
	return
}
