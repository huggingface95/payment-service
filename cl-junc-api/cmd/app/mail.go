package app

import (
	"encoding/json"
	"github.com/aaapi-net/liam"
)

func Mail(subject string, title string, details map[string]string, data interface{}, content string) error {
	msg := map[string]interface{}{"title": title, "details": details}

	if data != nil {
		dataJson, err := json.MarshalIndent(data, "", "  ")
		if err == nil {
			msg["data"] = string(dataJson)
		}
	}

	return getMailer().
		Subject(subject).
		Template(msg, string(content)).
		Send()
}

func getMailer() *liam.LMail {
	liam := liam.Liam{}
	config := Get.Config().App
	return liam.
		Smtp(config.Mail.Server, config.Mail.Port).
		Auth(config.Mail.Username, config.Mail.Password).
		AddTo(config.TechEmail)
}
