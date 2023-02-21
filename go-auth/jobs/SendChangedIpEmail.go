package jobs

import (
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/pkg"
	"jwt-authentication-golang/repositories"
	"jwt-authentication-golang/repositories/redisRepository"
)

func ProcessSendChangedIpEmailQueue() {
	for {
		redisData := redisRepository.GetRedisDataByBlPop(constants.QueueSendChangedIpEmail, func() interface{} {
			return new(cache.ConfirmationIpLinksCache)
		})
		if redisData == nil {
			break
		}
		sendEmailByData(redisData.(*cache.ConfirmationIpLinksCache))
	}
}

func sendEmailByData(e *cache.ConfirmationIpLinksCache) {
	template := repositories.GetEmailTemplateWithConditions(
		map[string]interface{}{"company_id": e.CompanyId},
		map[string]interface{}{"name": "New IP Detected"},
	)
	if template != nil {
		content := helpers.ReplaceData(template.Content,
			"{client_name}", e.FullName,
			"{client_datetime_login}", e.CreatedAt,
			"{client_ip}", e.Ip,
			"{change_ip_confirm_link}", convertConfirmationIp("auth/ip", e.ConfirmationLink),
		)
		err := pkg.Mail(template.Subject, content, e.Email)
		if err != nil {
			pkg.Error().Err(err)
			return
		}
	} else {
		pkg.Error().Msgf("email template not found:company_id:%d:name:New IP Detected", e.CompanyId)
	}
}
