package jobs

import (
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/pkg"
	"jwt-authentication-golang/repositories"
	"jwt-authentication-golang/repositories/redisRepository"
)

func ProcessSendConfirmationEmailQueue() {
	for {
		redisData := redisRepository.GetRedisDataByBlPop(constants.QueueSendIndividualConfirmEmail, func() interface{} {
			return new(cache.ConfirmationEmailLinksCache)
		})
		if redisData == nil {
			break
		}
		sendConfirmationEmailByData(redisData.(*cache.ConfirmationEmailLinksCache))
	}
}

func sendConfirmationEmailByData(e *cache.ConfirmationEmailLinksCache) {
	//TODO ADD COMPANY ID CONDITION
	template := repositories.GetEmailTemplateWithConditions(
		map[string]interface{}{},
		map[string]interface{}{"name": "Sign Up: Email Confirmation"},
	)
	if template != nil {
		content := helpers.ReplaceData(template.Content,
			"{client_name}", e.FullName,
			"{email_confirm_url}", convertConfirmationLink("auth/login", e.ConfirmationLink, e.Email, e.Type),
		)
		err := pkg.Mail(template.Subject, content, e.Email)
		if err != nil {
			pkg.Error().Err(err)
			return
		}
	} else {
		pkg.Error().Msgf("email template not found:name:Sign Up: Email Confirmation")
	}
}
