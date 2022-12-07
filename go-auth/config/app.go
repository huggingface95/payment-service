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
	CheckIpAddress             bool
	PasswordRequiredCharacters string
}

func (a *AppConfig) Load() *AppConfig {
	checkIp, err := strconv.ParseBool(os.Getenv("CHECK_IP"))
	if err != nil {
		panic(err)
	}
	checkIpAddress, err := strconv.ParseBool(os.Getenv("CHECK_IP_ADDRESS"))
	if err != nil {
		panic(err)
	}
	a.CheckIp = checkIp
	a.CheckIpAddress = checkIpAddress
	a.AppName = os.Getenv("APP_NAME")
	a.AppUrl = os.Getenv("APP_URL")
	a.AppFrontUrl = os.Getenv("APP_FRONT_URL")
	a.RedirectUrl = os.Getenv("REDIRECT_URL")
	a.PasswordRequiredCharacters = os.Getenv("PASSWORD_REQUIRED_CHARACTERS")

	return a
}
