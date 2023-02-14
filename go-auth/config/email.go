package config

import (
	"os"
	"strconv"
)

var EMailConf = EmailConfig{}

type EmailConfig struct {
	Server     string
	Port       int
	Username   string
	Password   string
	Encryption string
	From       string
	Mail       string
}

func (e *EmailConfig) Load() *EmailConfig {
	port, err := strconv.Atoi(os.Getenv("EMAIL_PORT"))
	if err != nil {
		panic(err)
	}
	e.Port = port
	e.Server = os.Getenv("EMAIL_SERVER")
	e.Username = os.Getenv("EMAIL_USERNAME")
	e.Password = os.Getenv("EMAIL_PASSWORD")
	e.Encryption = os.Getenv("EMAIL_ENCRYPTION")
	e.From = os.Getenv("EMAIL_FROM")
	e.Mail = os.Getenv("EMAIL_FROM_MAIL")

	return e
}
