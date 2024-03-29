package services

import (
	"errors"
	"fmt"
	"github.com/golang-jwt/jwt/v4"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/repositories/oauthRepository"
	"jwt-authentication-golang/times"
	"strconv"
	"strings"
	"time"
)

type JWTClaim struct {
	Prv  string `json:"prv"`
	Type string `json:"type"`

	jwt.RegisteredClaims
}

func (j *JWTClaim) GetId() uint64 {
	id, err := strconv.ParseUint(j.ID, 10, 64)
	if err != nil {
		return 0
	}
	return id
}

func getJWTClaims(id uint64, name string, provider string, accessType string, host string) *JWTClaim {
	IssuedTime, _, _, _, expirationJWTTime := times.GetTokenTimes()

	return &JWTClaim{
		Prv:  fmt.Sprint(provider),
		Type: accessType,
		RegisteredClaims: jwt.RegisteredClaims{
			Issuer:    host,
			IssuedAt:  jwt.NewNumericDate(IssuedTime),
			ExpiresAt: jwt.NewNumericDate(expirationJWTTime),
			NotBefore: jwt.NewNumericDate(IssuedTime),
			Subject:   name,
			ID:        strconv.Itoa(int(id)),
		},
	}
}

func GenerateJWT(id uint64, name string, provider string, jwtType string, accessType string, host string, testMode string) (token string, expirationTime time.Time, err error) {
	jwtObject := cache.Caching.Jwt.Get(fmt.Sprintf(constants.CacheJwt, host, jwtType, accessType, provider, id))
	if jwtObject != nil {
		_, err = parseJWT(jwtObject.Token, jwtType, host, testMode)
		if err == nil {
			expirationTime = jwtObject.ExpiredAt
			token = jwtObject.Token
			return
		}
	}

	claims := getJWTClaims(id, name, provider, accessType, host)
	oauthClient := oauthRepository.GetOauthClientByType(provider, jwtType)
	if oauthClient == nil {
		err = errors.New("oauth client not found")
		return
	}

	token, err = GetToken(claims, oauthClient.Secret)

	expirationTime = claims.ExpiresAt.Time

	data := &cache.JwtCache{
		Token:     token,
		ExpiredAt: expirationTime,
	}
	data.Set(fmt.Sprintf(constants.CacheJwt, host, jwtType, accessType, provider, id))

	return
}

func GetToken(claims jwt.Claims, secret string) (tokenString string, err error) {

	token := jwt.NewWithClaims(jwt.SigningMethodHS256, claims)
	tokenString, err = token.SignedString([]byte(secret))

	return
}

func parseJWT(signedToken string, jwtType string, host string, testMode string) (claims *JWTClaim, err error) {
	signedToken = strings.Replace(signedToken, "Bearer ", "", -1)

	oauthClients := oauthRepository.GetOauthClients(jwtType)

	for _, provider := range []string{constants.Member, constants.Individual, constants.Corporate} {
		token, err := new(jwt.Parser).ParseWithClaims(signedToken, &JWTClaim{}, func(token *jwt.Token) (interface{}, error) {
			return []byte(oauthClients[provider].Secret), nil
		})
		tMode, _ := strconv.ParseBool(testMode)
		if tMode {
			claims, ok := token.Claims.(*JWTClaim)
			if ok {
				return claims, nil
			}
		}

		if err == nil && token.Valid {
			claims, ok := token.Claims.(*JWTClaim)
			if ok {
				if config.Conf.Jwt.CheckIssuer == true && claims.Issuer != host {
					err = errors.New("Another Issuer")
				} else if claims.ExpiresAt.Unix() < time.Now().UTC().Unix() {
					err = errors.New("token expired")
				}
				return claims, err
			}
		}
	}
	err = errors.New("token not working")
	return claims, err
}

func ValidateAccessToken(token string, jwtType string, host string, testMode string) (err error) {
	claims, err := parseJWT(token, jwtType, host, testMode)
	if err == nil {
		if claims.Type != constants.AccessToken {
			err = errors.New("bad access token")
			return
		}
	}
	return
}

func ValidateForTwoFactorToken(token string, jwtType string, host string, testMode string) (err error) {
	claims, err := parseJWT(token, jwtType, host, testMode)
	if err == nil {
		if claims.Type != constants.ForTwoFactor {
			err = errors.New("bad two factor token")
			return
		}
	}
	return
}

func GetClaims(token string, jwtType string, replaceBearer bool, host string, testMode string) (claims *JWTClaim, err error) {
	return parseJWT(token, jwtType, host, testMode)
}
