package access

import (
	"github.com/juliangruber/go-intersect"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/permissionRepository"
	"regexp"
)

func CheckOperation(user postgres.User, name string, referer string) bool {
	referer = optimizeReferer(referer)

	if permissionRepository.IsGlobalOperation(name) == true {
		return true
	}

	operation := permissionRepository.GetStandardOperation(name, referer)

	if operation != nil {
		permissions := permissionRepository.GetUserPermissions(user)

		bindPermissions := intersect.Simple(operation.BindPermissions, permissions)

		parentPermissions := intersect.Simple(operation.ParentPermissions, permissions)

		if len(operation.BindPermissions) > 0 {
			if len(bindPermissions) > 0 && len(operation.ParentPermissions) == 0 {
				return true
			} else if len(bindPermissions) > 0 && len(parentPermissions) > 0 {
				return true
			}
		} else if len(parentPermissions) > 0 {
			return true
		}

	}

	return false
}

func optimizeReferer(url string) string {

	m1 := regexp.MustCompile(`.*?dashboard/(.*)`)
	m2 := regexp.MustCompile(`\?.*`)
	m3 := regexp.MustCompile(`[0-9]+`)

	url = m1.ReplaceAllString(url, "$1")
	url = m2.ReplaceAllLiteralString(url, "")
	url = m3.ReplaceAllLiteralString(url, "$id")

	return url
}
