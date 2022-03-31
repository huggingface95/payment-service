package clearjunction

import (
	"cl-junc-api/pkg/utils"
	"fmt"
	"net/http"
	"strings"
	"time"
)

// date YYYY-MM-DDThh:mm:ss+00:00
func (cj *ClearJunction) GetDate() string {
	return cj.FormatDate(time.Now())
}

func (cj *ClearJunction) FormatDate(date time.Time) string {
	return date.UTC().Format("2006-01-02T15:04:05+00:00")
}

func (cj *ClearJunction) getRequestSignature(body string) string {
	return cj.GetSignature(body, cj.GetDate())
}

func (cj *ClearJunction) GetSignature(body string, date string) string {
	return utils.GetSHA512SignatureS(
		[]byte(strings.ToUpper(
			fmt.Sprint(cj.config.Key, date, utils.GetSHA512SignatureS([]byte(cj.config.Password)), body))))
}

func (cj *ClearJunction) getHeaders(body string) http.Header {
	return http.Header{
		"Content-Type":  []string{"application/json"},
		"Date":          []string{cj.GetDate()},
		"X-API-KEY":     []string{cj.config.Key},
		"Authorization": []string{"Bearer " + cj.getRequestSignature(body)},
	}
}
