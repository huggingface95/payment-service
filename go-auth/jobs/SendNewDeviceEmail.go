package jobs

import (
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/pkg"
	"jwt-authentication-golang/repositories"
	"jwt-authentication-golang/repositories/redisRepository"
)

func ProcessSendNewDeviceEmailQueue() {
	for {
		redisData := redisRepository.GetRedisDataByBlPop(constants.QueueSendNewDeviceEmail, func() interface{} {
			return new(cache.ConfirmationNewDeviceData)
		})
		if redisData == nil {
			break
		}
		sendNewDeviceEmailByData(redisData.(*cache.ConfirmationNewDeviceData))
	}
}

func sendNewDeviceEmailByData(e *cache.ConfirmationNewDeviceData) {
	template := repositories.GetEmailTemplateWithConditions(
		map[string]interface{}{"company_id": e.CompanyId},
		map[string]interface{}{"name": "Devices: New Device Detected"},
	)
	if template != nil {
		content := helpers.ReplaceData(template.Content,
			"{client_name}", e.FullName,
			"{created_at}", e.CreatedAt,
			"{ip}", e.Ip,
			"{device_details}", convertDeviceDetails(e.Os, e.Model, e.Browser, e.Type),
		)
		err := pkg.Mail(template.Name, content, e.Email)
		if err != nil {
			pkg.Error().Err(err)
			return
		}
	} else {
		pkg.Error().Msgf("email template not found:company_id:%d:name:Devices: New Device Detected", e.CompanyId)
	}
}
