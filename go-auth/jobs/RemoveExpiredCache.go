package jobs

import (
	"fmt"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"time"
)

func ProcessRemoveExpiredCache() {
	deleteLoginAttempt()
	deleteTwoFactorAttempt()
	deleteBlackList()
	deleteBlockedAccounts()
	deleteConfirmationEmails()
	deleteConfirmationIps()
	deleteConfirmationNewDevices()
	deleteResetPassword()
	deleteTotp()
}

func deleteLoginAttempt() {
	records := database.GetKeys(fmt.Sprintf(constants.CacheLoginAttempt, "*"))
	for _, id := range records {
		r := cache.Caching.LoginAttempt.Get(id, true)
		if r != nil && r.ExpiredAt.Unix() <= time.Now().Unix() {
			r.Del(id)
		}
	}
}

func deleteTwoFactorAttempt() {
	records := database.GetKeys(fmt.Sprintf(constants.CacheTwoFactorLoginAttempt, "*"))
	for _, id := range records {
		r := cache.Caching.TwoFactorAttempt.Get(id, true)
		if r != nil && r.ExpiredAt.Unix() <= time.Now().Unix() {
			r.Del(id)
		}
	}
}

func deleteBlackList() {
	records := database.GetKeys(fmt.Sprintf(constants.CacheAuthBlackList, "*"))
	for _, id := range records {
		r := cache.Caching.BlackList.Get(id, true)
		if r != nil && r.ExpiredAt.Unix() <= time.Now().Unix() {
			r.Del(id)
		}
	}
}

func deleteBlockedAccounts() {
	records := database.GetKeys(fmt.Sprintf(constants.CacheBlockedAccounts, "*"))
	for _, id := range records {
		r := cache.Caching.BlockedAccounts.Get(id, true)
		if r != nil && r.ExpiredAt.Unix() <= time.Now().Unix() {
			r.Del(id)
		}
	}
}

func deleteConfirmationEmails() {
	records := database.GetKeys(fmt.Sprintf(constants.CacheConfirmationEmailLinks, "*"))
	for _, id := range records {
		r := cache.Caching.ConfirmationEmailLinks.Get(id, true)
		if r != nil && r.ExpiredAt.Unix() <= time.Now().Unix() {
			r.Del(id)
		}
	}
}

func deleteConfirmationIps() {
	records := database.GetKeys(fmt.Sprintf(constants.CacheConfirmationIpLinks, "*"))
	for _, id := range records {
		r := cache.Caching.ConfirmationIpLinks.Get(id, true)
		if r != nil && r.ExpiredAt.Unix() <= time.Now().Unix() {
			r.Del(id)
		}
	}
}

func deleteConfirmationNewDevices() {
	records := database.GetKeys(fmt.Sprintf(constants.CacheConfirmationNewDevice, "*"))
	for _, id := range records {
		r := cache.Caching.ConfirmationNewDevice.Get(id, true)
		if r != nil && r.ExpiredAt.Unix() <= time.Now().Unix() {
			r.Del(id)
		}
	}
}

func deleteResetPassword() {
	records := database.GetKeys(fmt.Sprintf(constants.CacheResetPassword, "*"))
	for _, id := range records {
		r := cache.Caching.ResetPassword.Get(id, true)
		if r != nil && r.ExpiredAt.Unix() <= time.Now().Unix() {
			r.Del(id)
		}
	}
}

func deleteTotp() {
	records := database.GetKeys(fmt.Sprintf(constants.CacheTotp, "*"))
	for _, id := range records {
		r := cache.Caching.Totp.Get(id, true)
		if r != nil && r.ExpiredAt.Unix() <= time.Now().Unix() {
			r.Del(id)
		}
	}
}
