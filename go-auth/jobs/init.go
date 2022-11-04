package jobs

import (
	"fmt"
	"jwt-authentication-golang/config"
	"time"
)

const JobPeriod = time.Second * 10

func sendChangedIpEmail() {
	ProcessSendChangedIpEmailQueue()
}

func sendConfirmationEmail() {
	ProcessSendConfirmationEmailQueue()
}

func sendNewDeviceEmail() {
	ProcessSendNewDeviceEmailQueue()
}

func sendTrustedDeviceEmail() {
	ProcessSendTrustedDeviceEmailQueue()
}

func sendConvertPasswordRecoveryLink() {
	ProcessConvertPasswordRecoveryLinkEmailQueue()
}

func Init() {
	for range time.Tick(JobPeriod) {
		sendChangedIpEmail()
		sendConfirmationEmail()
		sendConvertPasswordRecoveryLink()
		sendNewDeviceEmail()
		sendTrustedDeviceEmail()
	}
}

func convertConfirmationLink(path string, token string) string {
	return fmt.Sprintf("%s/auth/individual/confirmation/%s?token=%s", config.Conf.App.AppUrl, path, token)
}

func convertPasswordRecoveryLink(path string, token string) string {
	return fmt.Sprintf("%s/auth/individual/confirmation/%s?token=%s", config.Conf.App.AppFrontUrl, path, token)
}

func convertDeviceDetails(os string, model string, browser string, deviceType string) string {
	return fmt.Sprintf("%s, %s, %s, %s", os, model, browser, deviceType)
}
