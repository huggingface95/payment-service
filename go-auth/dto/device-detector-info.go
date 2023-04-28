package dto

import (
	"encoding/json"
	"github.com/gamebtc/devicedetector"
	"github.com/gin-gonic/gin"
	"io"
	"jwt-authentication-golang/requests"
	"jwt-authentication-golang/responses"
	"net/http"
)

type DeviceDetectorInfo struct {
	detector            *devicedetector.DeviceDetector
	Model               string
	Brand               string
	Type                string
	OsVersion           string
	OsName              string
	OsShortName         string
	OsPlatform          string
	ClientName          string
	ClientType          string
	ClientVersion       string
	ClientShortName     string
	ClientEngine        string
	ClientEngineVersion string
	BotName             string
	Ip                  string
	Country             string
	City                string
	Domain              string
	Lang                string
}

func (d *DeviceDetectorInfo) Parse(context *gin.Context) *DeviceDetectorInfo {
	var header requests.OperationHeaders
	errHeader := context.ShouldBindHeader(&header)

	if errHeader != nil {
		header.Origin = "PROBLEM DETECT DOMAIN"
	}

	info := d.detector.Parse(context.Request.UserAgent())
	os := info.GetOs()
	client := info.GetClient()
	bot := info.GetBot()
	ip := context.ClientIP()
	lang := context.GetHeader("Accept-Language")
	ipInfo := parseIp(ip)

	if ipInfo != nil {
		d.Country = ipInfo.Country
		d.City = ipInfo.City
	}

	d.Lang = lang
	d.Ip = ip
	d.Model = info.Model
	d.Brand = info.Brand
	d.Type = info.Type
	d.OsVersion = os.Version
	d.OsShortName = os.ShortName
	d.OsName = os.Name
	d.OsPlatform = os.Platform
	d.ClientType = client.Type
	d.ClientName = client.Name
	d.ClientVersion = client.Version

	if client.Type == `browser` {
		d.ClientShortName = client.ShortName
		d.ClientEngine = client.Engine
		d.ClientEngineVersion = client.EngineVersion
	}

	if bot != nil {
		d.BotName = bot.Name
	}

	d.Domain = header.Origin

	return d
}

// TODO move to services
func parseIp(ip string) *responses.Ip {
	var response *responses.Ip
	res, err := http.Get("http://ip-api.com/json/" + ip)

	if err != nil {
		return nil
	}

	defer func(Body io.ReadCloser) {
		err := Body.Close()
		if err != nil {

		}
	}(res.Body)

	if err := json.NewDecoder(res.Body).Decode(&response); err != nil {
		return nil
	}

	return response
}
