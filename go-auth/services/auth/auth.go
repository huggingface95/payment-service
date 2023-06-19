package auth

import (
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/services"
)

func GetAuthUserFromRequest(c *gin.Context) postgres.User {

	var bearerJWT requests.BearerJWT
	var inputJWT requests.InputJWT
	var routeJWT requests.RouteJWT
	var claims *services.JWTClaim
	var e error

	if err := c.BindHeader(&bearerJWT); err == nil {
		claims, e = services.GetClaims(bearerJWT.Bearer, constants.Personal, true, c.Request.Host)
	} else if err := c.BindJSON(&inputJWT); err == nil {
		claims, e = services.GetClaims(inputJWT.Token, constants.Personal, false, c.Request.Host)
	} else if err := c.BindUri(&routeJWT); err == nil {
		claims, e = services.GetClaims(routeJWT.Token, constants.Personal, false, c.Request.Host)
	} else {
		return nil
	}
	if e != nil {
		return nil
	}

	return userRepository.GetUserById(claims.GetId(), claims.Prv)
}

func GetAuthUserByToken(jwtType string, jwtAccessType string, token string, host string) postgres.User {
	var err error
	if jwtAccessType == constants.AccessToken {
		err = services.ValidateAccessToken(token, jwtType, host)
	} else if jwtAccessType == constants.ForTwoFactor {
		err = services.ValidateForTwoFactorToken(token, jwtType, host)
	}
	if err != nil {
		return nil
	}
	claims, err := services.GetClaims(token, jwtType, false, host)

	return userRepository.GetUserById(claims.GetId(), claims.Prv)
}

func CheckUserByToken(twaToken string, accessToken string, memberId uint64, clientType string, host string) (user postgres.User, errorMessage string) {
	if twaToken != "" {
		user = GetAuthUserByToken(constants.Personal, constants.ForTwoFactor, twaToken, host)
		errorMessage = "TwoFaToken not working"
	} else if accessToken != "" {
		user = GetAuthUserByToken(constants.Personal, constants.AccessToken, accessToken, host)
		errorMessage = "Auth token not working"
	} else {
		errorMessage = "two factor token or Access token required"
	}

	if user == nil {
		return
	}

	if memberId > 0 && clientType == constants.Member {
		user = userRepository.GetUserById(memberId, clientType)
		if user == nil {
			errorMessage = "Member not found"
		}
	}
	return
}
