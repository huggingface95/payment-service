package access

import (
	"fmt"
	"github.com/juliangruber/go-intersect"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/permissionRepository"
	"jwt-authentication-golang/requests"
	"regexp"
)

func checkOperation(permissions []postgres.Permission, name string, referer string) (bool, []interface{}) {
	if permissionRepository.IsGlobalOperation(name) == true {
		return true, nil
	}
	operation := permissionRepository.GetStandardOperation(name, referer)

	if operation != nil {

		bindPermissions := intersect.Simple(operation.BindPermissions, permissions)

		parentPermissions := intersect.Simple(operation.ParentPermissions, permissions)

		if len(operation.BindPermissions) > 0 {
			if len(bindPermissions) > 0 && len(operation.ParentPermissions) == 0 {
				return true, bindPermissions
			} else if len(bindPermissions) > 0 && len(parentPermissions) > 0 {
				return true, append(bindPermissions, parentPermissions...)
			}
		} else if len(parentPermissions) > 0 {
			return true, parentPermissions
		}
	}
	return false, nil
}

func checkModule(individual *postgres.Individual, verifiedPermissions []interface{}) (bool, string) {
	for _, m := range individual.ApplicantModuleActivity {
		for _, verifiedPermission := range verifiedPermissions {
			permission := verifiedPermission.(postgres.Permission)
			if permission.PermissionList.PermissionCategory.CheckActivityModule(m.Module) {
				return false, fmt.Sprintf("Permission %s not access in module %s", permission.DisplayName, m.Module.Name)
			}
		}
	}

	return true, ""
}

func CheckAccess(user postgres.User, inputs []requests.OperationInputs, referer string) (bool, string) {
	var message string
	referer = optimizeReferer(referer)
	permissions := permissionRepository.GetUserPermissions(user)

	for _, input := range inputs {
		ok, verifiedPermissions := checkOperation(permissions, input.OperationName, referer)
		if ok && len(verifiedPermissions) > 0 {
			if user.ClientType() == constants.Individual {
				ok, message := checkModule(user.(*postgres.Individual), verifiedPermissions)
				if ok == false {
					return false, message
				}
			}
		} else if ok && len(verifiedPermissions) == 0 {
			return true, message
		} else {
			return false, fmt.Sprintf("You are not authorized to access %s", input.OperationName)
		}
	}

	return true, message
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
