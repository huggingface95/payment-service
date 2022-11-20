package controllers

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/oauthRepository"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/services"
	"jwt-authentication-golang/services/auth"
	"jwt-authentication-golang/times"
	"net/http"
)

func Login(context *gin.Context) {
	var user postgres.User

	deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(context)

	newTime, blockedTime, _, oauthCodeTime, _ := times.GetTokenTimes()

	request, headerRequest, err := auth.ParseRequest(context)

	if err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	user = userRepository.GetUserByEmail(request.Email, request.Type)
	if user == nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": "User not found"})
		return
	}
	var key = fmt.Sprintf("%s_%d", request.Type, user.GetId())

	if auth.AttemptLimitEqual(key, blockedTime) {
		context.JSON(http.StatusForbidden, gin.H{"error": fmt.Sprint("Account is temporary blocked for ", blockedTime.Sub(newTime))})
		return
	}

	if auth.AttemptLimitLarge(key) {
		user.SetIsActivated(false)
		userRepository.SaveUser(user)
		context.JSON(http.StatusForbidden, gin.H{"error": "Account is blocked. Please contact support"})
		return
	}

	if checkPassword(context, user, request) == false {
		loginAttempt, _ := cache.Caching.LoginAttempt.Get(key)
		cache.Caching.LoginAttempt.Set(key, loginAttempt+1)
		return
	}

	if user.IsEmailVerify() == false {
		context.JSON(http.StatusForbidden, gin.H{"error": "Verifyed your email"})
		return
	}

	if request.Type == constants.Individual {
		if ok, s, data := checkAndUpdateSession(request.Type, user.GetEmail(), user.GetFullName(), user.GetCompanyId(), deviceInfo, context); ok == false {
			context.JSON(s, data)
			return
		}
	}

	if user.IsActivated() == false {
		context.JSON(http.StatusForbidden, gin.H{"error": "Account is blocked. Please contact support"})
		return
	}

	if auth.IsBlockedAccount(key) {
		context.JSON(http.StatusForbidden, gin.H{"error": "Your account temporary blocked. Try again later"})
		return
	}

	createAuthLogDto := dto.DTO.CreateAuthLogDto.Parse(request.Type, user.GetEmail(), user.GetCompany().Name, deviceInfo)

	if config.Conf.App.CheckIp {
		if request.Cancel {
			context.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
			return
		}
		authLog := oauthRepository.GetAuthLogWithConditions(map[string]string{"status": "login", "member": request.Email})
		ip := auth.GetUserIp(authLog, headerRequest.HttpClientIp, headerRequest.HttpXForwardedFor)
		if ip != context.ClientIP() || ip != "" {
			context.JSON(http.StatusUnauthorized, gin.H{"error": "This ID is currently in use on another device. Proceeding on this device, will automatically log out all other users."})
			return
		}

		browser := auth.GetUserBrowser(authLog)
		if browser != context.Request.UserAgent() || browser != "" {
			context.JSON(http.StatusUnauthorized, gin.H{"error": "This ID is currently in use on another device. Proceeding on this device, will automatically log out all other users."})
			return
		}

		if auth.GetAuthUser(authLog, createAuthLogDto) != "login" {
			context.JSON(http.StatusUnauthorized, gin.H{"error": "This ID is currently in use on another device. Proceeding on this device, will automatically log out all other users."})
			return
		}

		if request.Proceed {
			oauthRepository.InsertAuthLog("logout", createAuthLogDto)
		}
	}

	oauthRepository.InsertAuthLog("login", createAuthLogDto)

	if user.GetTwoFactorAuthSettingId() == 2 && user.IsGoogle2FaSecret() == false {
		tokenJWT, oauthClient, _, err := services.GenerateJWT(user.GetId(), user.GetFullName(), request.Type, constants.Personal, constants.ForTwoFactor)
		if err != nil {
			context.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		oauthRepository.CreateOauthCode(user.GetId(), oauthClient.Id, true, oauthCodeTime)
		context.JSON(http.StatusOK, gin.H{"2fa_token": tokenJWT})
		return
	}

	if user.GetTwoFactorAuthSettingId() == 2 && user.IsGoogle2FaSecret() == true {
		context.JSON(http.StatusOK, gin.H{"two_factor": "true", "auth_token": user.GetGoogle2FaSecret()})
	} else {
		cache.Caching.LoginAttempt.Delete(key)
	}

	if user.InClientIpAddresses(context.ClientIP()) == false {
		if auth.CreateConfirmationIpLink(request.Type, user.GetId(), user.GetCompanyId(), user.GetEmail(), context.ClientIP(), newTime) {
			context.JSON(http.StatusOK, gin.H{"data": "An email has been sent to your email to confirm the new ip"})
			return
		}
	}

	tokenJWT, _, expirationTime, err := services.GenerateJWT(user.GetId(), user.GetFullName(), request.Type, constants.Personal, constants.AccessToken)
	if err != nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}

	context.JSON(http.StatusOK, gin.H{"access_token": tokenJWT, "token_type": "bearer", "expires_in": expirationTime.Unix()})

}

func checkPassword(c *gin.Context, u postgres.User, r requests.LoginRequest) bool {
	credentialError := u.CheckPassword(r.Password)
	if credentialError != nil {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "invalid credentials"})
		c.Abort()
		return false
	}
	return true
}

func checkAndUpdateSession(provider string, email string, fullName string, companyId uint64, deviceInfo *dto.DeviceDetectorInfo, c *gin.Context) (bool, int, gin.H) {
	ok, err := oauthRepository.HasActiveSessionWithConditions(email, deviceInfo)

	if err != nil {
		return false, 403, gin.H{"data": "Failed to authorize device"}
	}
	if ok == true {
		return true, 200, nil
	}
	activeSessionCreated := oauthRepository.InsertActiveSessionLog(provider, email, false, false, deviceInfo)
	if activeSessionCreated != nil {
		ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendNewDeviceEmail, &cache.ConfirmationNewDeviceData{
			CompanyId: companyId,
			Email:     email,
			FullName:  fullName,
			CreatedAt: activeSessionCreated.CreatedAt.String(),
			Os:        deviceInfo.OsName,
			Type:      deviceInfo.Type,
			Browser:   deviceInfo.ClientEngine,
			Model:     deviceInfo.Model,
			Ip:        c.ClientIP(),
		})
		if ok {
			return false, 200, gin.H{"data": "An email has been sent to your email to confirm the new device"}
		}
	}

	return false, 403, gin.H{"data": "Failed to authorize device"}
}
