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
	"net/http/httputil"
	"net/url"
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

		if header.TestMode == false {
			if header.Referer != "" {
				ok, message := access.CheckAccess(jsonData, user, header.Referer)
				if ok == false {
					context.JSON(http.StatusBadRequest, gin.H{"message": message})
					return
				}
			} else {
				context.JSON(http.StatusBadRequest, gin.H{"message": "header pagereferer parameter required"})
				return
			}
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

func RedirectGetRequest(c *gin.Context) {
	remote, err := url.Parse(config.Conf.App.RedirectUrl + "/api")
	if err != nil {
		panic(err)
	}

	proxy := httputil.NewSingleHostReverseProxy(remote)
	proxy.Director = func(req *http.Request) {
		req.Header = c.Request.Header
		req.Host = remote.Host
		req.URL.Scheme = remote.Scheme
		req.URL.Host = remote.Host
		req.URL.Path = config.Conf.App.RedirectUrl + "/api"
	}

	proxy.ServeHTTP(c.Writer, c.Request)
}
