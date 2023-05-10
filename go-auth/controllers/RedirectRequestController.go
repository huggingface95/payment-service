package controllers

import (
	"bytes"
	"encoding/json"
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
	var operations []requests.OperationInputs
	var operation requests.OperationInputs
	var header requests.OperationHeaders
	var user postgres.User

	jsonData, errJson := context.GetRawData()
	errHeader := context.ShouldBindHeader(&header)
	errInput := json.Unmarshal(jsonData, &operations)

	if errInput.Error() == "json: cannot unmarshal object into Go value of type []requests.OperationInputs" {
		errInput = json.Unmarshal(jsonData, &operation)
		operations = append(operations, operation)
	}

	if errHeader != nil || errInput != nil || errJson != nil {
		context.JSON(http.StatusBadRequest, gin.H{"message": "Bad Request"})
		return
	}

	user = auth.GetAuthUserByToken(constants.Personal, constants.AccessToken, context.GetString("bearer"))

	if config.Conf.App.AppEnv != "testing" {
		ok, message := access.CheckAccess(user, operations, header.Referer)
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
