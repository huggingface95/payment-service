package jobs

import (
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/pkg"
	"jwt-authentication-golang/repositories"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/requests/redis"
)

func ProcessSendTrustedDeviceEmailQueue() {
	for {
		redisData := redisRepository.GetRedisDataByBlPop(constants.QueueSendTrustedDeviceEmail, func() interface{} {
			return new(redis.TrustedDeviceRequest)
		})
		if redisData == nil {
			break
		}
		sendTrustedDeviceEmailByData(redisData.(*redis.TrustedDeviceRequest))
	}
}

func sendTrustedDeviceEmailByData(e *redis.TrustedDeviceRequest) {
	template := repositories.GetEmailTemplateWithConditions(
		map[string]interface{}{"company_id": e.CompanyId},
		map[string]interface{}{"name": "Devices: New Trust Device has been added"},
	)
	if template != nil {
		content := helpers.ReplaceData(template.Content,
			"{client_name}", e.FullName,
			"{created_at}", e.CreatedAt,
			"{ip}", e.Ip,
			"{device_details}", convertDeviceDetails(e.Os, e.Model, e.Browser, e.Type),
		)
		err := pkg.Mail(template.Subject, content, e.Email)
		if err != nil {
			pkg.Error().Err(err)
			return
		}
	} else {
		pkg.Error().Msgf("email template not found:company_id:%d:name:Devices: New Trust Device has been added", e.CompanyId)
	}
}
