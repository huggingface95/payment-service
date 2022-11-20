package services

import (
	"errors"
	"fmt"
	"github.com/golang-jwt/jwt/v4"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/models/postgres"
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

func getJWTClaims(id uint64, name string, provider string, accessType string) *JWTClaim {
	IssuedTime, _, _, _, expirationJWTTime := times.GetTokenTimes()

	return &JWTClaim{
		Prv:  fmt.Sprint(provider),
		Type: accessType,
		RegisteredClaims: jwt.RegisteredClaims{
			Issuer:    config.Conf.Jwt.PayloadUrl,
			IssuedAt:  jwt.NewNumericDate(IssuedTime),
			ExpiresAt: jwt.NewNumericDate(expirationJWTTime),
			NotBefore: jwt.NewNumericDate(IssuedTime),
			Subject:   name,
			ID:        strconv.Itoa(int(id)),
		},
	}
}

func GenerateJWT(id uint64, name string, provider string, jwtType string, accessType string) (token string, oauthClient *postgres.OauthClient, expirationTime time.Time, err error) {
	claims := getJWTClaims(id, name, provider, accessType)
	oauthClient = oauthRepository.GetOauthClientByType(provider, jwtType)

	token, err = GetToken(claims, oauthClient.Secret)

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

func ValidateAccessToken(token string, jwtType string, replaceBearer bool) (err error) {
	claims, err := parseJWT(token, jwtType, replaceBearer)
	if err == nil {
		if claims.Type != constants.AccessToken {
			err = errors.New("bad access token")
			return
		}
	}
	return
}

func ValidateForTwoFactorTOken(token string, jwtType string, replaceBearer bool) (err error) {
	claims, err := parseJWT(token, jwtType, replaceBearer)
	fmt.Println(claims)
	if err == nil {
		if claims.Type != constants.ForTwoFactor {
			err = errors.New("bad two factor token")
			return
		}
	}
	return
}

func ValidateAuthToken(token string, jwtType string, replaceBearer bool) (err error) {
	claims, err := parseJWT(token, jwtType, replaceBearer)
	if err == nil {
		if claims.Type != constants.AuthToken {
			err = errors.New("bad two factor token")
			return
		}
	}
	return
}

func GetClaims(token string, jwtType string, replaceBearer bool) (claims *JWTClaim, err error) {
	return parseJWT(token, jwtType, replaceBearer)
}
