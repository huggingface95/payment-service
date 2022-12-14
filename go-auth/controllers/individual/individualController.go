package individual

import (
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/individualRepository"
	"jwt-authentication-golang/repositories/oauthRepository"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests/individual"
	"jwt-authentication-golang/services/auth"
	"net/http"
)

func Register(c *gin.Context) {
	var request individual.RegisterRequest
	if err := c.BindJSON(&request); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		c.Abort()
		return
	}

	if request.ClientType == constants.RegisterClientTypeCorporate {
		if request.CompanyName == "" || request.Url == "" {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Add required parameters"})
			c.Abort()
			return
		}
	}

	if ok, msg := auth.PasswordValidation(request.Password); ok == false {
		c.JSON(http.StatusBadRequest, gin.H{"error": msg})
		c.Abort()
		return
	}

	if request.Password != request.PasswordRepeat {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Password and Confirm Password do not match"})
		c.Abort()
		return
	}

	if userRepository.HasUserByEmail(request.Email, constants.Individual) == true {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Client has exists"})
		c.Abort()
		return
	}
	user, err := individualRepository.FillIndividual(request)
	company, err := individualRepository.FillCompany(request)
	user.TwoFactorAuthSettingId = 2
	user.IsVerificationPhone = postgres.ApplicantVerificationNotVerifyed
	user.IsVerificationEmail = postgres.ApplicantVerificationNotVerifyed

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		c.Abort()
		return
	}

	if err := user.HashPassword(request.Password); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		c.Abort()
		return
	}

	record := individualRepository.CreateIndividual(&user, &company, request.ClientType)

	if record == nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "internal server error"})
		c.Abort()
		return
	}

	if record.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": record.Error.Error()})
		c.Abort()
		return
	}

	randomToken := helpers.GenerateRandomString(20)
	//TODO ADD COMPANY ID
	data := &cache.ConfirmationEmailLinksData{
		Id: user.ID, FullName: user.FullName, ConfirmationLink: randomToken, Email: user.Email,
	}
	ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendIndividualConfirmEmail, data)
	if ok {
		cache.Caching.ConfirmationEmailLinks.Set(randomToken, data)
		c.JSON(http.StatusCreated, gin.H{"data": "An email has been sent to your email to confirm the email"})
		return
	}

	c.JSON(http.StatusForbidden, gin.H{"error": "Registration error"})
}

func ConfirmationIndividualEmail(context *gin.Context) {
	var user postgres.User
	deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(context)

	token := context.Request.URL.Query().Get("token")
	data, _ := cache.Caching.ConfirmationEmailLinks.Get(token)
	user = userRepository.GetUserById(data.Id, constants.Individual)
	if user != nil {
		if user.StructName() == constants.StructMember {
			user.SetIsActivated(postgres.MemberStatusActive)
			user.SetIsEmailVerify(postgres.MemberVerificationStatusActive)
		} else {
			user.SetIsEmailVerify(postgres.ApplicantVerificationVerifyed)
			user.SetIsActivated(postgres.ApplicantStateActive)
		}
		res := userRepository.SaveUser(user)
		if res.Error == nil {
			cache.Caching.ConfirmationEmailLinks.Delete(token)
			activeSessionLog := oauthRepository.InsertActiveSessionLog(constants.Individual, user.GetEmail(), true, true, deviceInfo)
			if activeSessionLog != nil {
				context.JSON(http.StatusOK, gin.H{"data": "Email Verified"})
				return
			}
		}
	}

	context.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
	context.Abort()
	return
}

func ResetPassword(c *gin.Context) {
	var user postgres.User

	var request individual.ResetPasswordRequest
	if err := c.BindJSON(&request); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		c.Abort()
		return
	}

	user = userRepository.GetUserByEmail(request.Email, constants.Individual)

	if user != nil {
		randomToken := helpers.GenerateRandomString(20)
		//TODO ADD COMPANY ID
		data := &cache.ResetPasswordCacheData{
			Id: user.GetId(), CompanyId: user.GetCompanyId(), FullName: user.GetFullName(), Email: user.GetEmail(), PasswordRecoveryUrl: randomToken,
		}
		ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendResetPasswordEmail, data)
		if ok {
			cache.Caching.ResetPassword.Set(randomToken, data)
			c.JSON(http.StatusOK, gin.H{"data": "An email has been sent to your email to click to link"})
			return
		}
	}
	c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
	return
}

func ChangePassword(c *gin.Context) {
	var user postgres.User

	var request individual.ChangePasswordRequest
	if err := c.BindJSON(&request); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	if request.Password != request.PasswordRepeat {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Password and Confirm Password do not match"})
		return
	}

	data, _ := cache.Caching.ResetPassword.Get(request.Token)
	user = userRepository.GetUserById(data.Id, constants.Individual)

	if user != nil {
		err := user.HashPassword(request.Password)
		if err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
			return
		}
		res := userRepository.SaveUser(user)
		if res.Error == nil {
			cache.Caching.ConfirmationEmailLinks.Delete(request.Token)
			c.JSON(http.StatusOK, gin.H{"data": "Password changed"})
			return
		}
	}

	c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
	return
}
