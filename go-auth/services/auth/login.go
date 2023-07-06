package auth

import (
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/oauthRepository"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/services"
	"net/http"
	"regexp"
	"strings"
	"time"
)

type LoginResponseDto struct {
	AccessToken         string `json:"access_token,omitempty"`
	TokenType           string `json:"token_type,omitempty"`
	ExpiresIn           int64  `json:"expires_in,omitempty"`
	IndividualStepToken string `json:"token,omitempty"`
	IndividualStepUrl   string `json:"url,omitempty"`
	Error               string `json:"error,omitempty"`
	TwoFaToken          string `json:"2fa_token,omitempty"`
	TwoFactor           bool   `json:"two_factor,omitempty"`
}

func GetLoginResponse(user postgres.User, jwtType string, accessType string, deviceInfo *dto.DeviceDetectorInfo, testMode string) (int, gin.H) {
	if user.ClientType() == constants.Individual {
		individual := user.(*postgres.Individual)
		if individual.IsCorporate() {
			token := (&cache.CorporateLoginCache{}).Set(individual.GetId(), individual.ApplicantCompany[0].Id)
			return http.StatusOK, gin.H{"url": "testurl", "token": token}
		}
	}

	tokenJWT, expirationTime, err := services.GenerateJWT(user.GetId(), user.GetFullName(), user.ClientType(), jwtType, accessType, deviceInfo.Host, testMode)
	if err != nil {
		oauthRepository.InsertAuthLog(user.ClientType(), user.GetEmail(), user.GetCompany().Name, constants.StatusFailed, nil, deviceInfo)
		return http.StatusInternalServerError, gin.H{"error": err.Error()}
	}

	if accessType == constants.ForTwoFactor {
		if user.IsGoogle2FaSecret() == false {
			return http.StatusOK, gin.H{"2fa_token": tokenJWT}
		} else {
			return http.StatusOK, gin.H{"2fa_token": tokenJWT, "two_factor": "true"}
		}
	}

	oauthRepository.InsertAuthLog(user.ClientType(), user.GetEmail(), user.GetCompany().Name, constants.StatusLogin, &expirationTime, deviceInfo)
	oauthRepository.InsertActiveSessionLog(user.ClientType(), user.GetEmail(), user.GetCompany().Name, true, true, &expirationTime, deviceInfo)
	return http.StatusOK, gin.H{"expires_in": expirationTime.Unix(), "access_token": tokenJWT, "token_type": "bearer"}
}

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
	attempt := cache.Caching.LoginAttempt.GetAttempt(key, false)
	if attempt == config.Conf.Jwt.MfaAttempts {
		cache.Caching.BlockedAccounts.Set(key, &blockedTime)
		cache.Caching.LoginAttempt.Set(key, attempt+1)
		return true
	}
	return false
}

func AttemptLimitLarge(key string) bool {
	attempt := cache.Caching.LoginAttempt.GetAttempt(key, false)
	if attempt >= config.Conf.Jwt.MfaAttempts {
		cache.Caching.LoginAttempt.Del(key)
		return true
	}
	return false
}

func IsBlockedAccount(key string) bool {
	return cache.Caching.BlockedAccounts.Has(key, false)
}

func CreateConfirmationIpLink(provider string, id uint64, companyId uint64, email string, clientIp string, timeNow time.Time) bool {
	randomToken := helpers.GenerateRandomString(20)
	data := &cache.ConfirmationIpLinksCache{
		CompanyId: companyId, Id: id, Email: email, Ip: clientIp, CreatedAt: timeNow.String(), ConfirmationLink: randomToken, Provider: provider,
	}
	ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendChangedIpEmail, data)
	if ok {
		data.Set(randomToken)
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

func CheckPassword(c *gin.Context, u postgres.User, r requests.LoginRequest) bool {
	credentialError := u.CheckPassword(r.Password)
	if credentialError != nil {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "invalid credentials"})
		c.Abort()
		return false
	}
	return true
}

func CheckAndUpdateSession(provider string, email string, fullName string, companyId uint64, companyName string, deviceInfo *dto.DeviceDetectorInfo, c *gin.Context) (bool, int, gin.H) {
	activeSessionCreated := oauthRepository.InsertActiveSessionLog(provider, email, companyName, true, false, nil, deviceInfo)
	if activeSessionCreated != nil {
		ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendNewDeviceEmail, &cache.ConfirmationNewDeviceCache{
			CompanyId: companyId,
			Email:     email,
			FullName:  fullName,
			CreatedAt: activeSessionCreated.CreatedAt.String(),
			Os:        deviceInfo.OsName,
			Type:      deviceInfo.Type,
			Browser:   deviceInfo.ClientEngine,
			Model:     deviceInfo.Model,
			Ip:        c.ClientIP(),
		})
		if ok {
			return false, 200, gin.H{"data": "An email has been sent to your email to confirm the new device"}
		}
	}

	return false, 403, gin.H{"data": "Failed to authorize device"}
}
