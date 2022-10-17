package services

import (
	"crypto/sha1"
	"encoding/hex"
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

	prv := fmt.Sprint(provider)
	h := sha1.New()
	h.Write([]byte(prv))
	sha := hex.EncodeToString(h.Sum(nil))

	return &JWTClaim{
		Prv: sha,
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

func parseJWT(provider string, signedToken string, jwtType string, replaceBearer bool) (claims *JWTClaim, err error) {
	if replaceBearer {
		signedToken = strings.Replace(signedToken, "Bearer ", "", -1)
	}

	oauthClient := oauthRepository.GetOauthClientByType(provider, jwtType)

	token, err := new(jwt.Parser).ParseWithClaims(signedToken, &JWTClaim{}, func(token *jwt.Token) (interface{}, error) {
		return []byte(oauthClient.Secret), nil
	})

	if err != nil {
		return
	}

	claims, ok := token.Claims.(*JWTClaim)
	if !ok {
		err = errors.New("couldn't parse claims")
		return
	}

	if claims.ExpiresAt.Unix() < time.Now().Local().Unix() {
		err = errors.New("token expired")
		return
	}

	return
}

func ValidateToken(provider string, token string, jwtType string, replaceBearer bool) (err error) {
	_, err = parseJWT(provider, token, jwtType, replaceBearer)
	return
}

func GetClaims(provider string, token string, jwtType string, replaceBearer bool) (claims *JWTClaim) {
	claims, err := parseJWT(provider, token, jwtType, replaceBearer)
	if err != nil {
		return nil
	}
	return claims
}
