package jobs

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/internal/db"
	"cl-junc-api/internal/redis/constants"
	"cl-junc-api/internal/redis/models"
)

const emailPayInType = "payin"
const emailPayoutType = "payout"

func ProcessEmailQueue() {
	emailList := getEmails()
	templates := getTemplates()

	for _, e := range emailList {
		if sendEmail(e, templates) != nil {
			//TODO if error add to redis log
		}
	}
}

func getTemplates() []*db.EmailTemplate {
	template := &db.EmailTemplate{}
	emailList := app.Get.Sql.SelectMapResult(template)

	newList := make([]*db.EmailTemplate, len(emailList))
	for _, t := range emailList {
		newList = append(newList, t.(*db.EmailTemplate))
	}

	return newList
}

func getEmails() []*models.Email {
	redisList := app.Get.GetRedisList(constants.QueueEmailLog, func() interface{} {
		return new(models.Email)
	})
	newList := make([]*models.Email, len(redisList))

	for _, c := range redisList {
		newList = append(newList, c.(*models.Email))
	}

	return newList
}

func getContentByType(templates []*db.EmailTemplate, t string) (string, error) {
	for _, tp := range templates {
		if tp.Type == t {
			return tp.Content, nil
		}
	}

	return "", nil
}

func sendEmail(e *models.Email, templates []*db.EmailTemplate) error {
	content, err := getContentByType(templates, e.Type)
	if err == nil {
		return app.Mail(e.Message, e.Status, e.Details, e.Data, content)
	}
	return err
}
