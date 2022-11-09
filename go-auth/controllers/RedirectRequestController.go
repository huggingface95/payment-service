package controllers

import (
	"bytes"
	"github.com/gin-gonic/gin"
	"io/ioutil"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/services/auth"
	"net/http"
)

func RedirectRequest(context *gin.Context) {
	var provider string

	jsonData, err := ioutil.ReadAll(context.Request.Body)
	if err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"message": "Bad Request"})
		return
	}
	user := auth.GetAuthUserFromRequest(context)

	if user.StructName() == constants.StructMember {
		provider = constants.Member
	} else {
		provider = constants.Individual
	}

	req, err := http.NewRequest("POST", config.Conf.App.RedirectUrl, bytes.NewBuffer(jsonData))
	req.Header.Set("referer", context.GetHeader("referer"))
	req.Header.Set("Content-Type", gin.MIMEJSON)
	req.Header.Set("Authorization", context.GetHeader("Authorization"))
	req.Header.Set("provider-type", provider)

	client := &http.Client{}
	resp, err := client.Do(req)
	if err != nil {
		panic(err)
	}

	defer resp.Body.Close()

	context.Data(resp.StatusCode, resp.Header.Get("Content-Type"), helpers.StreamToByte(resp.Body))

	//context.JSON(resp.StatusCode, customBody)
	//context.JSON(resp.StatusCode, helpers.StreamToByte(resp.Body))
}
