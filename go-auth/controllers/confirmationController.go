package controllers

import (
	"errors"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/pkg"
	"jwt-authentication-golang/repositories"
	"jwt-authentication-golang/repositories/oauthRepository"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests"
	"net/http"
)

func ConfirmationIp(context *gin.Context) {
	var model string
	token := context.Request.URL.Query().Get("token")
	data, _ := cache.Caching.ConfirmationIpLinks.Get(token)
	if data.Provider == constants.Individual {
		model = constants.ModelIndividual
	} else {
		model = constants.ModelMember
	}

	ipAddress := repositories.CreateClientIpAddress(data.Ip, data.Id, model)
	if ipAddress != nil {
		cache.Caching.ConfirmationIpLinks.Delete(token)
		if model == constants.ModelIndividual {
			deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(context)
			timeLineDto := dto.DTO.CreateTimeLineDto.Parse("Ip confirmation", "email", "Banking", data.CompanyId, data.Id, deviceInfo)
			ok := redisRepository.SetRedisDataByBlPop(constants.QueueAddTimeLineLog, timeLineDto)
			if ok == false {
				pkg.Error().Err(errors.New("TimeLine redis error"))
			}
		}

		context.JSON(http.StatusOK, gin.H{"data": "Ip added"})
		context.Abort()
		return
	}
	context.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
	context.Abort()
	return
}

func ConfirmationIndividualEmail(context *gin.Context) {
	var user postgres.User
	deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(context)

	token := context.Request.URL.Query().Get("token")
	data, _ := cache.Caching.ConfirmationEmailLinks.Get(token)
	clientType := constants.Member
	if data.Type == constants.Individual {
		clientType = constants.Individual
	}
	user = userRepository.GetUserById(data.Id, clientType)
	if user != nil {
		if user.StructName() == constants.StructMember {
			user.SetIsActivated(postgres.MemberStatusActive)
			user.SetIsEmailVerify(postgres.MemberVerificationStatusVerified)
		} else {
			user.SetIsEmailVerify(postgres.ApplicantVerificationVerifyed)
			user.SetIsActivated(postgres.ApplicantStateActive)
		}
		res := userRepository.SaveUser(user)
		if res.Error == nil {

			timeLineDto := dto.DTO.CreateTimeLineDto.Parse("Email Verified", "email", "Banking", data.CompanyId, data.Id, deviceInfo)
			ok := redisRepository.SetRedisDataByBlPop(constants.QueueAddTimeLineLog, timeLineDto)
			if ok == false {
				pkg.Error().Err(errors.New("TimeLine redis error"))
			}

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

func ChangePassword(c *gin.Context) {
	var user postgres.User
	var token = c.Request.URL.Query().Get("token")
	var request requests.ChangePasswordRequest
	if err := c.BindJSON(&request); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	if request.Password != request.PasswordRepeat {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Password and Confirm Password do not match"})
		return
	}

	data, _ := cache.Caching.ResetPassword.Get(token)
	user = userRepository.GetUserById(data.Id, clientType)

	if user != nil {
		err := user.HashPassword(request.Password)
		if err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
			return
		}
		user.SetNeedChangePassword(false)
		res := userRepository.SaveUser(user)
		if res.Error == nil {
			cache.Caching.ConfirmationEmailLinks.Delete(token)

			if clientType == constants.Individual {
				deviceInfo := dto.DTO.DeviceDetectorInfo.Parse(c)
				timeLineDto := dto.DTO.CreateTimeLineDto.Parse("Ip confirmation", "email", "Banking", data.CompanyId, data.Id, deviceInfo)
				ok := redisRepository.SetRedisDataByBlPop(constants.QueueAddTimeLineLog, timeLineDto)
				if ok == false {
					pkg.Error().Err(errors.New("TimeLine redis error"))
				}
			}

			c.JSON(http.StatusOK, gin.H{"data": "Password changed"})
			return
		}
	}

	c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
	return
}
