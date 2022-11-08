package jobs

import (
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/pkg"
	"jwt-authentication-golang/repositories"
	"jwt-authentication-golang/repositories/redisRepository"
)

func ProcessConvertPasswordRecoveryLinkEmailQueue() {
	for {
		redisData := redisRepository.GetRedisDataByBlPop(constants.QueueSendResetPasswordEmail, func() interface{} {
			return new(cache.ConfirmationIpLinksData)
		})
		if redisData == nil {
			break
		}
		sendPasswordRecoveryLinkEmailByData(redisData.(*cache.ResetPasswordCacheData))
	}
}

func sendPasswordRecoveryLinkEmailByData(e *cache.ResetPasswordCacheData) {
	template := repositories.GetEmailTemplateWithConditions(
		map[string]interface{}{"company_id": e.CompanyId},
		map[string]interface{}{"name": "Reset Password"},
	)
	if template != nil {
		content := helpers.ReplaceData(template.Content,
			"{client_name}", e.FullName,
			"{password_recovery_url}", convertPasswordRecoveryLink("test", e.PasswordRecoveryUrl),
		)
		err := pkg.Mail(content, content, e.Email)
		if err != nil {
			return
		}
	}
}
