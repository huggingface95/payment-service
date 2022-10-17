package auth

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/services"
)

func GetAuthUserFromRequest(c *gin.Context) postgres.User {
	var h requests.HeaderProviderRequest

	if err := c.BindHeader(&h); err != nil {
		fmt.Println("warning parent function GetAuthUserFromRequest  required PROVIDER_TYPE header")
		return nil
	}

	var bearerJWT requests.BearerJWT
	var inputJWT requests.InputJWT
	var routeJWT requests.RouteJWT
	var claims *services.JWTClaim

	if err := c.BindHeader(&bearerJWT); err == nil {
		claims = services.GetClaims(h.ProviderType, bearerJWT.Bearer, constants.Personal, true)
	} else if err := c.Bind(&inputJWT); err != nil {
		claims = services.GetClaims(h.ProviderType, inputJWT.Token, constants.Personal, false)
	} else if err := c.BindUri(&routeJWT); err != nil {
		claims = services.GetClaims(h.ProviderType, routeJWT.Token, constants.Personal, false)
	} else {
		return nil
	}

	return userRepository.GetUserById(claims.GetSubject(), claims.Prv)
}
