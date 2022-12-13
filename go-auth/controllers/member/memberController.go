package member

import (
	"bytes"
	"github.com/gin-gonic/gin"
	"io/ioutil"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/helpers"
	"net/http"
)

func Me(context *gin.Context) {
	jsonData, err := ioutil.ReadAll(context.Request.Body)
	if err != nil {
		context.JSON(http.StatusBadRequest, gin.H{"message": "Bad Request"})
		return
	}

	req, err := http.NewRequest("POST", config.Conf.App.RedirectUrl+"/auth/me", bytes.NewBuffer(jsonData))
	req.Header.Set("Authorization", context.GetHeader("Authorization"))

	client := &http.Client{}
	resp, err := client.Do(req)
	if err != nil {
		panic(err)
	}

	defer resp.Body.Close()

	context.Data(resp.StatusCode, resp.Header.Get("Content-Type"), helpers.StreamToByte(resp.Body))
}
