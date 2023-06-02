package jobs

import (
	"fmt"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/pkg"
	"jwt-authentication-golang/repositories"
	"jwt-authentication-golang/repositories/redisRepository"
)

func ProcessSendChangedIpEmailQueue() {
	for _, data := range redisRepository.GetRedisListByKey(fmt.Sprintf(constants.CacheConfirmationIpLinks, "*"), func() interface{} {
		return new(cache.ConfirmationIpLinksCache)
	}) {
		sendEmailByData(data.(*cache.ConfirmationIpLinksCache))
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
			"{created_at}", e.CreatedAt,
			"{ip}", e.Ip,
			"{change_ip_confirm_link}", convertConfirmationIp("auth/login", e.ConfirmationLink, e.Email),
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
