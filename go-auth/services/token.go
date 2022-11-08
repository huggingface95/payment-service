package services

import (
	"errors"
	"fmt"
	"github.com/golang-jwt/jwt/v4"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/oauthRepository"
	"jwt-authentication-golang/times"
	"strconv"
	"strings"
	"time"
)

type JWTClaim struct {
	Prv string `json:"prv,omitempty"`

	jwt.RegisteredClaims
}

func (j *JWTClaim) GetSubject() uint64 {
	number, err := strconv.ParseUint(j.Subject, 10, 64)
	if err != nil {
		return 0
	}
	return number
}

func getJWTClaims(id uint64, twoFactorAuthSettingId uint64, provider string) *JWTClaim {
	IssuedTime, _, _, _, expirationJWTTime := times.GetTokenTimes()

	return &JWTClaim{
		Prv: fmt.Sprint(provider),
		RegisteredClaims: jwt.RegisteredClaims{
			Audience:  []string{strconv.FormatUint(twoFactorAuthSettingId, 10)},
			Issuer:    config.Conf.Jwt.PayloadUrl,
			IssuedAt:  jwt.NewNumericDate(IssuedTime),
			ExpiresAt: jwt.NewNumericDate(expirationJWTTime),
			NotBefore: jwt.NewNumericDate(IssuedTime),
			Subject:   strconv.Itoa(int(id)),
			ID:        helpers.GenerateRandomString(20),
		},
	}
}

func GenerateJWT(id uint64, twoFactorAuthSettingId uint64, name string, provider string, jwtType string) (token string, oauthClient *postgres.OauthClient, expirationTime time.Time, err error) {
	claims := getJWTClaims(id, twoFactorAuthSettingId, provider)
	oauthClient = oauthRepository.GetOauthClientByType(provider, jwtType)

	token, err = GetToken(claims, oauthClient.Secret)
	if err == nil && jwtType == constants.GrantPassword {
		oauthRepository.CreateOauthAccessToken(&postgres.OauthAccessToken{
			ID:                claims.ID,
			UserId:            id,
			ClientId:          oauthClient.Id,
			Name:              name,
			Revoked:           false,
			TwoFactorVerified: false,
		})
	}
	expirationTime = claims.ExpiresAt.Time
	return
}

func GetToken(claims jwt.Claims, secret string) (tokenString string, err error) {

	token := jwt.NewWithClaims(jwt.SigningMethodHS256, claims)
	tokenString, err = token.SignedString([]byte(secret))

	return
}

func parseJWT(signedToken string, jwtType string, replaceBearer bool) (claims *JWTClaim, err error) {
	if replaceBearer {
		signedToken = strings.Replace(signedToken, "Bearer ", "", -1)
	}

	for _, provider := range []string{constants.Member, constants.Individual} {
		oauthClient := oauthRepository.GetOauthClientByType(provider, jwtType)
		token, err := new(jwt.Parser).ParseWithClaims(signedToken, &JWTClaim{}, func(token *jwt.Token) (interface{}, error) {
			return []byte(oauthClient.Secret), nil
		})
		if err == nil {
			claims, ok := token.Claims.(*JWTClaim)
			if ok {
				if claims.ExpiresAt.Unix() < time.Now().Local().Unix() {
					err = errors.New("token expired")
				}
				return claims, err
			}
		}
	}
	err = errors.New("token not working")
	return claims, err
}

func ValidateToken(token string, jwtType string, replaceBearer bool) (err error) {
	_, err = parseJWT(token, jwtType, replaceBearer)
	return
}

func GetClaims(token string, jwtType string, replaceBearer bool) (claims *JWTClaim, err error) {
	return parseJWT(token, jwtType, replaceBearer)
}
