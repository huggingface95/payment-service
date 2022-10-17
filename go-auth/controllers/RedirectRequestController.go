package controllers

import (
	"bytes"
	"github.com/gin-gonic/gin"
	"io/ioutil"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/helpers"
	"net/http"
)

func RedirectRequest(context *gin.Context) {
	jsonData, err := ioutil.ReadAll(context.Request.Body)
	if err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"message": "Bad Request"})
		return
	}

	req, err := http.NewRequest("POST", config.Conf.App.RedirectUrl, bytes.NewBuffer(jsonData))
	req.Header.Set("referer", context.GetHeader("referer"))
	req.Header.Set("Content-Type", gin.MIMEJSON)
	req.Header.Set("Authorization", context.GetHeader("Authorization"))

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
