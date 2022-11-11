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
	} else if err := c.Bind(&inputJWT); err == nil {
		claims, e = services.GetClaims(inputJWT.Token, constants.Personal, false)
	} else if err := c.BindUri(&routeJWT); err == nil {
		claims, e = services.GetClaims(routeJWT.Token, constants.Personal, false)
	} else {
		return nil
	}
	if e != nil {
		return nil
	}

	return userRepository.GetUserById(claims.GetSubject(), claims.Prv)
}
