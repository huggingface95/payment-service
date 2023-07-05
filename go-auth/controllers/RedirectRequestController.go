package controllers

import (
	"bytes"
	"github.com/gin-gonic/gin"
	"io/ioutil"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/pkg"
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
	var jsonData []byte

	if context.Request.Body != nil {
		jsonData, _ = ioutil.ReadAll(context.Request.Body)
	}

	user = auth.GetAuthUserByToken(constants.Personal, constants.AccessToken, context.GetHeader("Authorization"), context.Request.Host)

	context.Request.Body = ioutil.NopCloser(bytes.NewBuffer(jsonData))

	if config.Conf.App.AppEnv != "testing" {
		if err := context.ShouldBindHeader(&header); err != nil {
			context.JSON(http.StatusBadRequest, gin.H{"message": err.Error()})
			return
		}

		if header.TestMode == false {
			if header.Referer != "" {
				ok, message := access.CheckAccess(jsonData, user, header.Referer)
				if ok == false {
					pkg.Info().Msgf("pagereferer:%s , data:%s, message:%s, type:%s",
						context.GetHeader("pagereferer"),
						string(jsonData),
						message,
						user.ClientType(),
					)
					//context.JSON(http.StatusUnauthorized, gin.H{"message": message})
					//return
				}
			} else {
				context.JSON(http.StatusBadRequest, gin.H{"message": "header pagereferer parameter required"})
				return
			}
		}
	}

	sendProxy(context, "/api")
}

func RedirectGetRequest(c *gin.Context) {
	sendProxy(c, "/api")
}

func RedirectFilesRequest(c *gin.Context) {
	sendProxy(c, "/api/files")
}
func RedirectEmailRequest(c *gin.Context) {
	sendProxy(c, "/api/email")
}
func RedirectSmsRequest(c *gin.Context) {
	sendProxy(c, "/api/sms")
}
func RedirectPdfRequest(c *gin.Context) {
	sendProxy(c, "/api/pdf")
}

func sendProxy(c *gin.Context, path string) {
	remote, err := url.Parse(config.Conf.App.RedirectUrl + path)
	if err != nil {
		panic(err)
	}

	proxy := httputil.NewSingleHostReverseProxy(remote)
	proxy.Director = func(req *http.Request) {
		req.Header = c.Request.Header
		req.Host = remote.Host
		req.URL.Scheme = remote.Scheme
		req.URL.Host = remote.Host
		req.URL.Path = config.Conf.App.RedirectUrl + path
	}

	proxy.ServeHTTP(c.Writer, c.Request)
}
