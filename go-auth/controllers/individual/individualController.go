package individual

import (
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/repositories/individualRepository"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/repositories/userRepository"
	"jwt-authentication-golang/requests/individual"
	"jwt-authentication-golang/services/auth"
	"net/http"
)

func Register(c *gin.Context) {
	var request individual.RegisterRequest
	if err := c.BindJSON(&request); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		c.Abort()
		return
	}

	if request.ClientType == constants.RegisterClientTypeCorporate {
		if request.CompanyName == "" || request.Url == "" {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Add required parameters"})
			c.Abort()
			return
		}
	}

	if ok, msg := auth.PasswordValidation(request.Password); ok == false {
		c.JSON(http.StatusBadRequest, gin.H{"error": msg})
		c.Abort()
		return
	}

	if request.Password != request.PasswordRepeat {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Password and Confirm Password do not match"})
		c.Abort()
		return
	}

	if userRepository.HasUserByEmail(request.Email, constants.Individual) == true {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Client has exists"})
		c.Abort()
		return
	}

	err, user, company := individualRepository.CreateIndividual(request)

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		c.Abort()
		return
	}

	randomToken := helpers.GenerateRandomString(20)
	data := &cache.ConfirmationEmailLinksCache{
		Id: user.ID, FullName: user.FullName, ConfirmationLink: randomToken, Email: user.Email, CompanyId: company.Id,
	}
	ok := redisRepository.SetRedisDataByBlPop(constants.QueueSendIndividualConfirmEmail, data)
	if ok {
		data.Set(randomToken)
		c.JSON(http.StatusCreated, gin.H{"data": "An email has been sent to your email to confirm the email"})
		return
	}

	c.JSON(http.StatusForbidden, gin.H{"error": "Registration error"})
}
