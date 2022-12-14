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
		data := &cache.ResetPasswordCacheData{
			Id: user.GetId(), CompanyId: user.GetCompanyId(), FullName: user.GetFullName(), Email: user.GetEmail(), PasswordRecoveryUrl: randomToken,
		}
		ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendResetPasswordEmail, data)
		if ok {
			cache.Caching.ResetPassword.Set(randomToken, data)
			c.JSON(http.StatusOK, gin.H{"data": "An email has been sent to your email to click to link"})
			return
		}
	}
	c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
	return
}

func ChangePassword(c *gin.Context) {
	var user postgres.User

	var request requests.ChangePasswordRequest
	if err := c.BindJSON(&request); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	clientType := constants.Member
	if request.Type != "" {
		clientType = request.Type
	}

	if request.Password != request.PasswordRepeat {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Password and Confirm Password do not match"})
		return
	}

	data, _ := cache.Caching.ResetPassword.Get(request.Token)
	user = userRepository.GetUserById(data.Id, clientType)

	if user != nil {
		err := user.HashPassword(request.Password)
		if err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
			return
		}
		res := userRepository.SaveUser(user)
		if res.Error == nil {
			cache.Caching.ConfirmationEmailLinks.Delete(request.Token)
			c.JSON(http.StatusOK, gin.H{"data": "Password changed"})
			return
		}
	}

	c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal server error"})
	return
}
