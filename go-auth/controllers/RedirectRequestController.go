package controllers

import (
	"bytes"
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/services/access"
	"jwt-authentication-golang/services/auth"
	"net/http"
)

func RedirectRequest(context *gin.Context) {
	var user postgres.User
	var header requests.OperationHeaders

	jsonData, err := context.GetRawData()
	if err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"message": "Bad Request"})
		return
	}

	user = auth.GetAuthUserByToken(constants.Personal, constants.AccessToken, context.GetString("bearer"))

	if config.Conf.App.AppEnv != "testing" {
		if err := context.ShouldBindHeader(&header); err != nil {
			context.JSON(http.StatusBadRequest, gin.H{"message": err.Error()})
			return
		}

		ok, message := access.CheckAccess(jsonData, user, header.Referer)
		if ok == false {
			context.JSON(http.StatusBadRequest, gin.H{"message": message})
			return
		}
	}

	req, err := http.NewRequest("POST", config.Conf.App.RedirectUrl+"/api", bytes.NewBuffer(jsonData))
	req.Header.Set("Content-Type", gin.MIMEJSON)
	req.Header.Set("Authorization", context.GetHeader("Authorization"))

	client := &http.Client{}
	resp, err := client.Do(req)
	if err != nil {
		panic(err)
	}

	defer resp.Body.Close()

	context.Data(resp.StatusCode, resp.Header.Get("Content-Type"), helpers.StreamToByte(resp.Body))
}
