package jobs

import (
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/repositories/timelineRepository"
)

func ProcessAddTimeLineLogQueue() {
	for {
		redisData := redisRepository.GetRedisDataByBlPop(constants.QueueAddTimeLineLog, func() interface{} {
			return new(dto.CreateTimeLineDto)
		})
		if redisData == nil {
			break
		}
		addTimeLineLog(redisData.(*dto.CreateTimeLineDto))
	}
}

func addTimeLineLog(dto *dto.CreateTimeLineDto) {
	timelineRepository.InsertTimeLineLog(dto)
}
