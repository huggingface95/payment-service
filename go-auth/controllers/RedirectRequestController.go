package controllers

import (
	"bytes"
	"fmt"
	"github.com/gin-gonic/gin"
	"io/ioutil"
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
	var input requests.OperationInputs
	var header requests.OperationHeaders
	var user postgres.User

	errHeader := context.ShouldBindHeader(&header)
	errInput := context.ShouldBindJSON(&input)
	jsonData, errJson := ioutil.ReadAll(context.Request.Body)
	if errHeader != nil || errInput != nil || errJson != nil {
		context.JSON(http.StatusBadRequest, gin.H{"message": "Bad Request"})
		return
	}

	user = auth.GetAuthUserByToken(constants.Personal, constants.AccessToken, context.GetString("bearer"))

	//testTime, _ := time.Parse("2006-01-02", "2023-02-28")

	if config.Conf.App.AppEnv != "testing" || user.GetId() != 2 {
		if access.CheckOperation(user, input.OperationName, header.Referer) == false {
			context.JSON(http.StatusBadRequest, gin.H{"message": fmt.Sprintf("You are not authorized to access %s", input.OperationName)})
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
