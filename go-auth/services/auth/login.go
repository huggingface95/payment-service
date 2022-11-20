package auth

import (
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/clickhouse"
	"jwt-authentication-golang/repositories/oauthRepository"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/requests"
	"regexp"
	"strings"
	"time"
)

func ParseRequest(c *gin.Context) (r requests.LoginRequest, h requests.HeaderRequest, err error) {
	if err = c.BindJSON(&r); err != nil {
		return
	}

	if err = c.BindHeader(&h); err != nil {
		return
	}

	return
}

func AttemptLimitEqual(key string, blockedTime time.Time) bool {
	if attempt, ok := cache.Caching.LoginAttempt.Get(key); ok == true {
		if attempt == config.Conf.Jwt.MfaAttempts {
			cache.Caching.BlockedAccounts.Set(key, blockedTime.Unix())
			cache.Caching.LoginAttempt.Set(key, attempt+1)
			return true
		}
	}
	return false
}

func AttemptLimitLarge(key string) bool {
	if attempt, ok := cache.Caching.LoginAttempt.Get(key); ok == true {
		if attempt >= config.Conf.Jwt.MfaAttempts {
			cache.Caching.LoginAttempt.Delete(key)
			return true
		}
	}
	return false
}

func IsBlockedAccount(key string) bool {
	return cache.Caching.BlockedAccounts.Has(key)
}

func GetUserIp(authLog *clickhouse.AuthenticationLog, headerIp string, headerForwardedFor string) string {
	if authLog != nil {
		return authLog.Ip
	} else if headerForwardedFor != "" {
		return headerForwardedFor
	} else if headerIp != "" {
		return headerIp
	}
	return ""
}

func GetUserBrowser(authLog *clickhouse.AuthenticationLog) string {

	if authLog != nil {
		return authLog.Browser
	}

	return ""
}

func GetAuthUser(authLog *clickhouse.AuthenticationLog, dto *dto.CreateAuthLogDto) string {
	if authLog == nil {
		log := oauthRepository.InsertAuthLog("logout", dto)
		if log == nil {
			return ""
		} else {
			return log.Status
		}
	}

	return authLog.Status
}

func CreateConfirmationIpLink(provider string, id uint64, companyId uint64, email string, clientIp string, timeNow time.Time) bool {
	randomToken := helpers.GenerateRandomString(20)
	data := &cache.ConfirmationIpLinksData{
		CompanyId: companyId, Id: id, Email: email, Ip: clientIp, CreatedAt: timeNow.String(), ConfirmationLink: randomToken, Provider: provider,
	}
	ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendChangedIpEmail, data)
	if ok {
		cache.Caching.ConfirmationIpLinks.Set(randomToken, data)
		return true
	}

	return false
}

func PasswordValidation(password string) (bool, string) {
	validators := strings.Split(config.Conf.App.PasswordRequiredCharacters, ",")

	for _, validator := range validators {
		match, _ := regexp.MatchString(validator, password)
		if match == false {
			return false, "Password is not secure"
		}
	}
	return true, ""
}
