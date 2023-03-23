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
		claims, e = services.GetClaims(bearerJWT.Bearer, constants.Personal, true)
	} else if err := c.BindJSON(&inputJWT); err == nil {
		claims, e = services.GetClaims(inputJWT.Token, constants.Personal, false)
	} else if err := c.BindUri(&routeJWT); err == nil {
		claims, e = services.GetClaims(routeJWT.Token, constants.Personal, false)
	} else {
		return nil
	}
	if e != nil {
		return nil
	}

	return userRepository.GetUserById(claims.GetId(), claims.Prv)
}

func GetAuthUserByToken(jwtType string, jwtAccessType string, token string) postgres.User {
	var err error
	if jwtAccessType == constants.AccessToken {
		err = services.ValidateAccessToken(token, jwtType, false)
	} else if jwtAccessType == constants.ForTwoFactor {
		err = services.ValidateForTwoFactorToken(token, jwtType, false)
	} else {
		err = services.ValidateForTwoFactorToken(token, jwtType, false)
	}
	if err != nil {
		return nil
	}
	claims, err := services.GetClaims(token, jwtType, false)

	return userRepository.GetUserById(claims.GetId(), claims.Prv)
}

func CheckUserByToken(twaToken string, accessToken string, memberId uint64, clientType string) (user postgres.User, errorMessage string) {
	if twaToken != "" {
		user = GetAuthUserByToken(constants.Personal, constants.ForTwoFactor, twaToken)
		errorMessage = "TwoFaToken not working"
	} else if accessToken != "" {
		user = GetAuthUserByToken(constants.Personal, constants.AccessToken, accessToken)
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
