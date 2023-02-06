package controllers

import (
	"errors"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/pkg"
	"jwt-authentication-golang/repositories"
	"jwt-authentication-golang/repositories/redisRepository"
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
