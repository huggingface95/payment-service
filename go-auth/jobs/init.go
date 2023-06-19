package jobs

import (
	"fmt"
	"github.com/go-co-op/gocron"
	"jwt-authentication-golang/config"
	"time"
)

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

func sendAddTimeLineLog() {
	ProcessAddTimeLineLogQueue()
}

func removeExpiredCache() {
	ProcessRemoveExpiredCache()
}

func Init() {
	s := gocron.NewScheduler(time.UTC)
	_, _ = s.Every(10).Seconds().Do(func() {
		sendChangedIpEmail()
		sendConfirmationEmail()
		sendConvertPasswordRecoveryLink()
		sendNewDeviceEmail()
		sendTrustedDeviceEmail()
		sendAddTimeLineLog()
	})

	_, _ = s.Every(60).Seconds().Do(func() {
		removeExpiredCache()
	})

	s.StartAsync()
}

func convertConfirmationLink(path string, token string) string {
	return fmt.Sprintf("%s/%s?token=%s", config.Conf.App.AppFrontUrl, path, token)
}

func convertConfirmationIp(path string, token string, email string) string {
	return fmt.Sprintf("%s/%s?token=%s&email=%s", config.Conf.App.AppFrontUrl, path, token, email)
}

func convertPasswordRecoveryLink(path string, token string) string {
	return fmt.Sprintf("%s/%s?token=%s", config.Conf.App.AppFrontUrl, path, token)
}

func convertDeviceDetails(os string, model string, browser string, deviceType string) string {
	return fmt.Sprintf("%s, %s, %s, %s", os, model, browser, deviceType)
}
