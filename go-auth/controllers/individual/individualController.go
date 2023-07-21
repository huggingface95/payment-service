package individual

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
	"jwt-authentication-golang/repositories/individualRepository"
	"jwt-authentication-golang/repositories/oauthRepository"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests/individual"
	"jwt-authentication-golang/services/auth"
	"jwt-authentication-golang/times"
	"net/http"
	"reflect"
)

// TODO will need merge to Login function
func Authorize(context *gin.Context) {
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

	user = userRepository.GetWithConditions(map[string]interface{}{
		"email": request.Email,
	}, func() interface{} { return new(postgres.Individual) })
	if user == nil {
		context.JSON(http.StatusInternalServerError, gin.H{"error": "User not found"})
		return
	}
	var key = fmt.Sprintf("%s_%d", constants.Individual, user.GetId())

	activeSession = oauthRepository.HasActiveSessionWithConditions(user.GetEmail(), constants.Individual, deviceInfo)
	authLog = oauthRepository.HasAuthLogWithConditions(user.GetEmail(), constants.Individual, deviceInfo)

	if auth.AttemptLimitEqual(key, blockedTime) {
		oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		context.JSON(http.StatusForbidden, gin.H{"error": fmt.Sprint("Account is temporary blocked for ", blockedTime.Sub(newTime))})
		return
	}

	if auth.AttemptLimitLarge(key) {
		user.SetIsActivated(postgres.ApplicantStateBlocked)
		userRepository.SaveUser(user)
		oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		context.JSON(http.StatusForbidden, gin.H{"error": "Account is blocked. Please contact support"})
		return
	}

	if auth.CheckPassword(context, user, request) == false {
		attempt := cache.Caching.LoginAttempt.GetAttempt(key, false)
		cache.Caching.LoginAttempt.Set(key, attempt+1)
		oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		return
	}

	if user.IsEmailVerify() == false {
		randomToken := helpers.GenerateRandomString(20)
		data := &cache.ConfirmationEmailLinksCache{
			Id: user.GetId(), FullName: user.GetFullName(), ConfirmationLink: randomToken, Email: user.GetEmail(), CompanyId: user.GetCompanyId(), Type: constants.Individual,
		}
		ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendIndividualConfirmEmail, data)
		if ok == false {
			context.JSON(http.StatusInternalServerError, gin.H{"error": "An error occurred while sending your email `confirmation email`"})
			oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
			return
		}
		data.Set(randomToken)
		context.JSON(http.StatusForbidden, gin.H{"data": "An email has been sent to your email to confirm the email"})
		oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		return
	}

	if user.IsChangePassword() {
		randomToken := helpers.GenerateRandomString(20)
		data := &cache.ResetPasswordCache{
			Id: user.GetId(), CompanyId: user.GetCompanyId(), FullName: user.GetFullName(), Email: user.GetEmail(), PasswordRecoveryUrl: randomToken, Type: constants.Individual,
		}
		data.Set(randomToken)
		context.JSON(http.StatusForbidden, gin.H{
			"message": "Please change password first", "url": user.GetCompany().BackofficeForgotPasswordUrl, "password_reset_token": randomToken,
		})
		oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		return
	}

	if config.Conf.App.CheckDevice && activeSession == nil {
		if ok, s, data := auth.CheckAndUpdateSession(constants.Individual, user.GetEmail(), user.GetFullName(), user.GetCompanyId(), user.GetCompany().Name, deviceInfo, context); ok == false {
			context.JSON(s, data)
			return
		}
	}

	if user.IsActivated() == false {
		oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		context.JSON(http.StatusForbidden, gin.H{"error": "Account is blocked. Please contact support"})
		return
	}

	if auth.IsBlockedAccount(key) {
		oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		context.JSON(http.StatusForbidden, gin.H{"error": "Your account temporary blocked. Try again later"})
		return
	}

	if config.Conf.App.CheckLoginDevice && authLog != nil {
		var proceed = false
		if request.Proceed {
			proceed = true
			oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		}

		if request.Cancel {
			oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
			context.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
			return
		}

		if authLog.Status == constants.StatusLogin && proceed == false {
			oauthRepository.InsertAuthLog(constants.Individual, user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
			context.JSON(http.StatusUnauthorized, gin.H{"error": "This ID is currently in use on another device. Proceeding on this device, will automatically log out all other users."})
			return
		}

	}

	if config.Conf.App.CheckIp {
		if user.InClientIpAddresses(context.ClientIP()) == false {
			if auth.CreateConfirmationIpLink(constants.Individual, user.GetId(), user.GetCompanyId(), user.GetEmail(), context.ClientIP(), newTime) {
				context.JSON(http.StatusOK, gin.H{"data": "An email has been sent to your email to confirm the new ip"})
				return
			}
		}
	}

	if user.GetTwoFactorAuthSettingId() == 2 {
		status, response := auth.GetLoginResponse(user, constants.Personal, constants.ForTwoFactor, deviceInfo, context.GetHeader("test-mode"))
		context.JSON(status, response)
		return
	} else {
		cache.Caching.LoginAttempt.Del(key)
	}

	status, response := auth.GetLoginResponse(user, constants.Personal, constants.AccessToken, deviceInfo, context.GetHeader("test-mode"))
	context.JSON(status, response)
	return
}

func Register(c *gin.Context) {
	request, res, status := fillRegisterRequest(c, func() interface{} { return new(individual.RegisterRequest) })
	if status != 200 {
		c.JSON(status, res)
		c.Abort()
		return
	}

	if request.(*individual.RegisterRequest).ClientType == constants.RegisterClientTypeCorporate {
		if request.(*individual.RegisterRequest).CompanyName == "" {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Add required parameters CompanyName"})
			c.Abort()
			return
		}
	} else {
		if request.(*individual.RegisterRequest).FirstName == "" || request.(*individual.RegisterRequest).LastName == "" {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Add required parameters FirstName LatName"})
			c.Abort()
			return
		}
	}

	err, user, company := individualRepository.CreateIndividual(request)

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		c.Abort()
		return
	}

	randomToken := helpers.GenerateRandomString(20)
	data := &cache.ConfirmationEmailLinksCache{
		Id: user.Id, FullName: user.FullName, ConfirmationLink: randomToken, Email: user.Email, CompanyId: company.Id, Type: constants.Individual,
	}
	ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendIndividualConfirmEmail, data)
	if ok {
		data.Set(randomToken)
		c.JSON(http.StatusCreated, gin.H{"data": "An email has been sent to your email to confirm the email"})
		return
	}

	c.JSON(http.StatusForbidden, gin.H{"error": "Registration error"})
}

func RegisterPrivate(c *gin.Context) {
	request, res, status := fillRegisterRequest(c, func() interface{} { return new(individual.RegisterRequestPrivate) })
	if status != 200 {
		c.JSON(status, res)
		c.Abort()
		return
	}

	err, user, company := individualRepository.CreateIndividual(request)

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		c.Abort()
		return
	}

	randomToken := helpers.GenerateRandomString(20)
	data := &cache.ConfirmationEmailLinksCache{
		Id: user.Id, FullName: user.FullName, ConfirmationLink: randomToken, Email: user.Email, CompanyId: company.Id, Type: constants.Individual,
	}
	ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendIndividualConfirmEmail, data)
	if ok {
		data.Set(randomToken)
		c.JSON(http.StatusCreated, gin.H{"data": "An email has been sent to your email to confirm the email"})
		return
	}

	c.JSON(http.StatusForbidden, gin.H{"error": "Registration error"})
}

func RegisterCorporate(c *gin.Context) {
	request, res, status := fillRegisterRequest(c, func() interface{} { return new(individual.RegisterRequestCorporate) })
	if status != 200 {
		c.JSON(status, res)
		c.Abort()
		return
	}

	err, user, company := individualRepository.CreateIndividual(request)

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		c.Abort()
		return
	}

	randomToken := helpers.GenerateRandomString(20)
	data := &cache.ConfirmationEmailLinksCache{
		Id: user.Id, FullName: user.FullName, ConfirmationLink: randomToken, Email: user.Email, CompanyId: company.Id, Type: constants.Individual,
	}
	ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendIndividualConfirmEmail, data)
	if ok {
		data.Set(randomToken)
		c.JSON(http.StatusCreated, gin.H{"data": "An email has been sent to your email to confirm the email"})
		return
	}

	c.JSON(http.StatusForbidden, gin.H{"error": "Registration error"})
}

func fillRegisterRequest(c *gin.Context, f func() interface{}) (request individual.RegisterApplicantInterface, res gin.H, status int) {
	var err error
	model := f()
	if reflect.TypeOf(model).Elem().Name() == individual.RegisterPrivate {
		err = c.BindJSON(&model)
	} else if reflect.TypeOf(model).Elem().Name() == individual.RegisterCorporate {
		err = c.BindJSON(&model)
	} else {
		err = c.BindJSON(&model)
	}

	request = reflect.ValueOf(model).Interface().(individual.RegisterApplicantInterface)

	if err != nil {
		return request, gin.H{"error": err.Error()}, http.StatusBadRequest
	}

	if ok, msg := auth.PasswordValidation(request.GetPassword()); ok == false {
		return request, gin.H{"error": msg}, http.StatusBadRequest
	}

	if request.GetPassword() != request.GetPasswordRepeat() {
		return request, gin.H{"error": "Password and Confirm Password do not match"}, http.StatusBadRequest
	}

	ok, _ := userRepository.HasWithConditions(map[string]interface{}{
		"email":      request.GetEmail(),
		"company_id": request.GetCompanyId(),
		"project_id": request.GetProjectId(),
	}, func() interface{} { return new(postgres.Individual) })

	if ok == true {
		return request, gin.H{"error": "Client has exists"}, http.StatusBadRequest
	}

	return request, nil, 200
}
