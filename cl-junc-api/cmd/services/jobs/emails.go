package jobs

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	"cl-junc-api/internal/redis/models"
	"cl-junc-api/pkg/utils/log"
)

func ProcessEmailQueue() {
	emailList := getEmails()
	templates := getTemplates()

	for _, e := range emailList {
		template, err := getTemplate(templates, e.Type)
		if err == nil {
			if sendEmail(e, template) != nil {
				//TODO if error add to redis log
			}
		} else {
			log.Error().Err(err)
		}
	}
}

func getTemplates() []*db.EmailTemplates {
	var emailList []*db.EmailTemplates
	app.Get.Sql.SelectMapResult(&emailList)
	log.Debug().Msgf("jobs: getTemplates: emailList: %#v", emailList)

	return emailList
}

func getEmails() []*models.Email {
	redisList := app.Get.GetRedisList(constants.QueueEmailLog, func() interface{} {
		return new(models.Email)
	})
	var newList []*models.Email

	for _, c := range redisList {
		newList = append(newList, c.(*models.Email))
	}

	return newList
}

func getTemplate(templates []*db.EmailTemplates, t string) (*db.EmailTemplates, error) {
	for _, tp := range templates {
		if tp.Type == t {
			return tp, nil
		}
	}

	return nil, nil
}

func sendEmail(e *models.Email, template *db.EmailTemplates) error {
	log.Debug().Msgf("jobs: sendEmail: data", e.Message, e.Status, e.Details, e.Data, template.Content)
	return app.Mail(e.Message, e.Status, e.Details, e.Data, template.Content)
}
