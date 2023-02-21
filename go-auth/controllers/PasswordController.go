package controllers

import (
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests"
	"net/http"
)

func ResetPassword(c *gin.Context) {
	var user postgres.User

	var request requests.ResetPasswordRequest
	if err := c.BindJSON(&request); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		c.Abort()
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	user = userRepository.GetUserByEmail(request.Email, clientType)

	if user != nil {
		randomToken := helpers.GenerateRandomString(20)
		data := &cache.ResetPasswordCache{
			Id: user.GetId(), CompanyId: user.GetCompanyId(), FullName: user.GetFullName(), Email: user.GetEmail(), PasswordRecoveryUrl: randomToken, Type: clientType,
		}
		ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendResetPasswordEmail, data)
		if ok {
			data.Set(randomToken)
			c.JSON(http.StatusOK, gin.H{"data": "An email has been sent to your email to click to link"})
			return
		}
	}
	c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
	return
}
