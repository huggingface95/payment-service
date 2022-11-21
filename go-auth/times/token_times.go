package times

import (
	"jwt-authentication-golang/config"
	"time"
)

func GetTokenTimes() (newTime time.Time, blockedTime time.Time, authUserTime time.Time, oauthCodeTime time.Time, expirationJWTTime time.Time) {
	newTime = time.Now().Local()
	blockedTime = newTime.Add(time.Second * time.Duration(config.Conf.Jwt.BlockAccountTtl))
	authUserTime = newTime.Add(time.Second * time.Duration(config.Conf.Jwt.BlockAccountTtl))
	oauthCodeTime = newTime.Add(time.Second * time.Duration(900))
	expirationJWTTime = time.Now().Local().Add(1 * time.Hour)
	return
}
