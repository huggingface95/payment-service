package config

import (
	"os"
	"strconv"
)

var AppConf = AppConfig{}

type AppConfig struct {
	AppName                    string
	AppDebug                   bool
	AppUrl                     string
	AppFrontUrl                string
	RedirectUrl                string
	CheckIp                    bool
	SendEmail                  bool
	CheckLoginDevice           bool
	CheckDevice                bool
	PasswordRequiredCharacters string
}

func (a *AppConfig) Load() *AppConfig {
	checkIp, err := strconv.ParseBool(os.Getenv("CHECK_IP"))
	if err != nil {
		panic(err)
	}
	sendEmail, err := strconv.ParseBool(os.Getenv("EMAIL_CONNECTION"))
	if err != nil {
		panic(err)
	}
	checkLoginDevice, err := strconv.ParseBool(os.Getenv("CHECK_LOGIN_DEVICE"))
	if err != nil {
		panic(err)
	}
	checkDevice, err := strconv.ParseBool(os.Getenv("CHECK_DEVICE"))
	if err != nil {
		panic(err)
	}
	a.CheckIp = checkIp
	a.SendEmail = sendEmail
	a.CheckLoginDevice = checkLoginDevice
	a.CheckDevice = checkDevice
	a.AppName = os.Getenv("APP_NAME")
	a.AppUrl = os.Getenv("APP_URL")
	a.AppFrontUrl = os.Getenv("APP_FRONT_URL")
	a.RedirectUrl = os.Getenv("REDIRECT_URL")
	a.PasswordRequiredCharacters = os.Getenv("PASSWORD_REQUIRED_CHARACTERS")

	return a
}
