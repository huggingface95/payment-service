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
	"jwt-authentication-golang/services/auth"
	"jwt-authentication-golang/times"
	"net/http"
)

func Login(context *gin.Context) {
	var user postgres.User
	var activeSession *clickhouse.ActiveSession
	var authLog *clickhouse.AuthenticationLog

	deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(context)

	newTime, blockedTime, _, _, _ := times.GetTokenTimes()

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
	authLog = oauthRepository.HasAuthLogWithConditions(user.GetEmail(), clientType, deviceInfo)

	if auth.AttemptLimitEqual(key, blockedTime) {
		oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
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
		oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		context.JSON(http.StatusForbidden, gin.H{"error": "Account is blocked. Please contact support"})
		return
	}

	if auth.CheckPassword(context, user, request) == false {
		attempt := cache.Caching.LoginAttempt.GetAttempt(key, false)
		cache.Caching.LoginAttempt.Set(key, attempt+1)
		oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		return
	}

	if user.IsEmailVerify() == false {
		randomToken := helpers.GenerateRandomString(20)
		data := &cache.ConfirmationEmailLinksCache{
			Id: user.GetId(), FullName: user.GetFullName(), ConfirmationLink: randomToken, Email: user.GetEmail(), CompanyId: user.GetCompanyId(), Type: clientType,
		}
		ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendIndividualConfirmEmail, data)
		if ok == false {
			context.JSON(http.StatusInternalServerError, gin.H{"error": "An error occurred while sending your email `confirmation email`"})
			oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
			return
		}
		data.Set(randomToken)
		context.JSON(http.StatusForbidden, gin.H{"data": "An email has been sent to your email to confirm the email"})
		oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		return
	}

	if user.IsChangePassword() {
		randomToken := helpers.GenerateRandomString(20)
		data := &cache.ResetPasswordCache{
			Id: user.GetId(), CompanyId: user.GetCompanyId(), FullName: user.GetFullName(), Email: user.GetEmail(), PasswordRecoveryUrl: randomToken, Type: clientType,
		}
		data.Set(randomToken)
		context.JSON(http.StatusForbidden, gin.H{
			"message": "Please change password first", "url": user.GetCompany().BackofficeForgotPasswordUrl, "password_reset_token": randomToken,
		})
		oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		return
	}

	if clientType == constants.Individual && config.Conf.App.CheckDevice && activeSession == nil {
		if ok, s, data := auth.CheckAndUpdateSession(clientType, user.GetEmail(), user.GetFullName(), user.GetCompanyId(), user.GetCompany().Name, deviceInfo, context); ok == false {
			context.JSON(s, data)
			return
		}
	}

	if user.IsActivated() == false {
		oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		context.JSON(http.StatusForbidden, gin.H{"error": "Account is blocked. Please contact support"})
		return
	}

	if auth.IsBlockedAccount(key) {
		oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		context.JSON(http.StatusForbidden, gin.H{"error": "Your account temporary blocked. Try again later"})
		return
	}

	if config.Conf.App.CheckLoginDevice && authLog != nil {
		var proceed = false
		if request.Proceed {
			proceed = true
			oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		}

		if request.Cancel {
			oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
			context.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
			return
		}

		if authLog.Status == constants.StatusLogin && proceed == false {
			oauthRepository.InsertAuthLog(clientType, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
			context.JSON(http.StatusUnauthorized, gin.H{"error": "This ID is currently in use on another device. Proceeding on this device, will automatically log out all other users."})
			return
		}

	}

	if config.Conf.App.CheckIp || (user.ClientType() == constants.Individual && user.GetId() == 24) {
		if user.InClientIpAddresses(context.ClientIP()) == false {
			if auth.CreateConfirmationIpLink(clientType, user.GetId(), user.GetCompanyId(), user.GetEmail(), context.ClientIP(), newTime) {
				context.JSON(http.StatusOK, gin.H{"data": "An email has been sent to your email to confirm the new ip"})
				return
			}
		}
	}

	if clientType == constants.Individual {
		individual := user.(*postgres.Individual)
		if individual.HasApplicantModuleActivity() == false {
			context.JSON(http.StatusUnauthorized, gin.H{"error": "you need to enable the module activity"})
			return
		}
	}

	if user.GetTwoFactorAuthSettingId() == 2 {
		if activeSession != nil && activeSession.Trusted == true {
			status, response := auth.GetLoginResponse(user, constants.Personal, constants.AccessToken, deviceInfo, context.GetHeader("test-mode"))
			context.JSON(status, response)
		} else {
			status, response := auth.GetLoginResponse(user, constants.Personal, constants.ForTwoFactor, deviceInfo, context.GetHeader("test-mode"))
			context.JSON(status, response)
		}
		return
	} else {
		cache.Caching.LoginAttempt.Del(key)
	}

	status, response := auth.GetLoginResponse(user, constants.Personal, constants.AccessToken, deviceInfo, context.GetHeader("test-mode"))
	context.JSON(status, response)
	return
}

func SelectAccount(c *gin.Context) {
	var r requests.SelectedAccountRequest
	var user postgres.User
	if err := c.BindJSON(&r); err != nil {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Token not working"})
		return
	}

	deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(c)

	corporateCache := cache.Caching.CorporateLogin.Get(r.Token)

	if corporateCache == nil {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Token not working"})
		return
	} else {
		corporateCache.Del(r.Token)
	}

	if r.Type == constants.Corporate {
		user = userRepository.GetCorporateById(corporateCache.Data[constants.Corporate], corporateCache.Data[constants.Individual])
	} else {
		user = userRepository.GetUserById(corporateCache.Data[constants.Individual], constants.Individual)
	}

	status, response := auth.GetLoginResponse(user, constants.Personal, constants.AccessToken, deviceInfo, c.GetHeader("test-mode"))
	c.JSON(status, response)
	return
}
