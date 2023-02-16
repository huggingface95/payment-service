package controllers

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/clickhouse"
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
	var activeSession *clickhouse.ActiveSession
	var authLog *clickhouse.AuthenticationLog
	//var authLogActive *clickhouse.AuthenticationLog

	deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(context)

	newTime, blockedTime, _, oauthCodeTime, expirationJWTTime := times.GetTokenTimes()

	request, _, err := auth.ParseRequest(context)

	if err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	user = userRepository.GetUserByEmail(request.Email, clientType)
	if user == nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": "User not found"})
		return
	}
	var key = fmt.Sprintf("%s_%d", clientType, user.GetId())

	activeSession = oauthRepository.HasActiveSessionWithConditions(user.GetEmail(), clientType, deviceInfo)
	//authLogActive = oauthRepository.HasActiveAuthLogWithConditions(user.GetEmail(), clientType, deviceInfo)
	authLog = oauthRepository.HasAuthLogWithConditions(user.GetEmail(), clientType, deviceInfo)

	if auth.AttemptLimitEqual(key, blockedTime) {
		context.JSON(http.StatusForbidden, gin.H{"error": fmt.Sprint("Account is temporary blocked for ", blockedTime.Sub(newTime))})
		return
	}

	if auth.AttemptLimitLarge(key) {
		if user.StructName() == constants.StructMember {
			user.SetIsActivated(postgres.MemberStatusSuspended)
		} else {
			user.SetIsActivated(postgres.ApplicantStateBlocked)
		}
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
		randomToken := helpers.GenerateRandomString(20)
		data := &cache.ConfirmationEmailLinksData{
			Id: user.GetId(), FullName: user.GetFullName(), ConfirmationLink: randomToken, Email: user.GetEmail(), CompanyId: user.GetCompanyId(), Type: clientType,
		}
		ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendIndividualConfirmEmail, data)
		if ok == false {
			context.JSON(http.StatusInternalServerError, gin.H{"error": "An error occurred while sending your email `confirmation email`"})
			return
		}
		cache.Caching.ConfirmationEmailLinks.Set(randomToken, data)

		context.JSON(http.StatusForbidden, gin.H{"data": "An email has been sent to your email to confirm the email"})
		return
	}

	if user.IsChangePassword() {
		randomToken := helpers.GenerateRandomString(20)
		data := &cache.ResetPasswordCacheData{
			Id: user.GetId(), CompanyId: user.GetCompanyId(), FullName: user.GetFullName(), Email: user.GetEmail(), PasswordRecoveryUrl: randomToken, Type: clientType,
		}

		cache.Caching.ResetPassword.Set(randomToken, data)
		context.JSON(http.StatusForbidden, gin.H{
			"message": "Please change password first", "url": user.GetCompany().BackofficeForgotPasswordUrl, "password_reset_token": randomToken,
		})
		return
	}

	if clientType == constants.Individual && config.Conf.App.CheckDevice && activeSession == nil {
		if ok, s, data := checkAndUpdateSession(clientType, user.GetEmail(), user.GetFullName(), user.GetCompanyId(), deviceInfo, context); ok == false {
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

	if config.Conf.App.CheckLoginDevice && authLog != nil {
		var proceed = false
		if request.Proceed {
			proceed = true
			oauthRepository.InsertAuthLog(clientType, user.GetEmail(), "logout", expirationJWTTime, deviceInfo)
		}

		if request.Cancel {
			context.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
			return
		}

		if authLog.Status == "login" && proceed == false {
			context.JSON(http.StatusUnauthorized, gin.H{"error": "This ID is currently in use on another device. Proceeding on this device, will automatically log out all other users."})
			return
		}

	}

	if config.Conf.App.CheckIp {
		if user.InClientIpAddresses(context.ClientIP()) == false {
			if auth.CreateConfirmationIpLink(clientType, user.GetId(), user.GetCompanyId(), user.GetEmail(), context.ClientIP(), newTime) {
				context.JSON(http.StatusOK, gin.H{"data": "An email has been sent to your email to confirm the new ip"})
				return
			}
		}
	}

	if activeSession == nil {
		activeSession = oauthRepository.InsertActiveSessionLog(clientType, user.GetEmail(), true, true, deviceInfo)
	}
	oauthRepository.InsertAuthLog(clientType, user.GetEmail(), "login", expirationJWTTime, deviceInfo)

	if user.GetTwoFactorAuthSettingId() == 2 && user.IsGoogle2FaSecret() == false {
		tokenJWT, oauthClient, _, err := services.GenerateJWT(user.GetId(), user.GetFullName(), clientType, constants.Personal, constants.ForTwoFactor)
		if err != nil {
			context.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		oauthRepository.CreateOauthCode(user.GetId(), oauthClient.Id, true, oauthCodeTime)
		context.JSON(http.StatusOK, gin.H{"2fa_token": tokenJWT})
		return
	}

	if user.GetTwoFactorAuthSettingId() == 2 && user.IsGoogle2FaSecret() == true {
		authToken, _, _, err := services.GenerateJWT(user.GetId(), user.GetFullName(), clientType, constants.Personal, constants.AuthToken)
		if err != nil {
			context.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}

		context.JSON(http.StatusOK, gin.H{"two_factor": "true", "auth_token": authToken})
		return
	} else {
		cache.Caching.LoginAttempt.Delete(key)
	}

	tokenJWT, _, expirationTime, err := services.GenerateJWT(user.GetId(), user.GetFullName(), clientType, constants.Personal, constants.AccessToken)
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
