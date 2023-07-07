package access

import (
	"encoding/json"
	"fmt"
	"github.com/graphql-go/graphql/language/ast"
	"github.com/graphql-go/graphql/language/parser"
	"github.com/juliangruber/go-intersect"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/permissionRepository"
	"jwt-authentication-golang/requests"
	"net/url"
	"regexp"
)

func checkOperation(permissions []postgres.Permission, name string, method string, t string, referer string) (bool, []interface{}) {
	if permissionRepository.IsGlobalOperation(name, method, t) == true {
		return true, nil
	}
	operation := permissionRepository.GetStandardOperation(name, method, t, referer)

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

func CheckAccess(jsonData []byte, user postgres.User, referer string) (bool, string) {
	var operationInput requests.OperationInputs
	var operationInputs []requests.OperationInputs
	var operationDetails []requests.OperationDetails

	var message string

	if jsonData[0] == '{' {
		if err := json.Unmarshal(jsonData, &operationInput); err != nil {
			return false, err.Error()
		}
		operationInputs = append(operationInputs, operationInput)
	} else if jsonData[0] == '[' {
		if err := json.Unmarshal(jsonData, &operationInputs); err != nil {
			return false, err.Error()
		}
	} else {
		return false, "json unmarshal error"
	}

	for _, oInput := range operationInputs {
		z, err := parser.Parse(parser.ParseParams{Source: oInput.Query})
		if err != nil {
			return false, err.Error()
		}
		for _, d := range z.Definitions {
			if operationDefinition, ok := d.(*ast.OperationDefinition); ok {
				details := requests.OperationDetails{}
				if operationDefinition.GetName() != nil {
					details.Operation = operationDefinition.GetName().Value
				}
				if len(operationDefinition.SelectionSet.Selections) == 1 {
					details.Method = operationDefinition.SelectionSet.Selections[0].(*ast.Field).Name.Value
				}
				details.Type = operationDefinition.GetOperation()

				operationDetails = append(operationDetails, details)
			}
		}
	}

	referer = optimizeReferer(referer)
	permissions := permissionRepository.GetUserPermissions(user)

	for _, input := range operationDetails {
		ok, verifiedPermissions := checkOperation(permissions, input.Operation, input.Method, input.Type, referer)
		if ok && len(verifiedPermissions) > 0 {
			if user.ClientType() == constants.Individual {
				ok, message := checkModule(user.(*postgres.Individual), verifiedPermissions)
				if ok == false {
					return false, message
				}
			} else if user.ClientType() == constants.Member {
				for _, verifiedPermission := range verifiedPermissions {
					fmt.Println(verifiedPermission)
					//permission := verifiedPermission.(postgres.Permission)
					//if permission.Name {
					//
					//}
				}
			}
		} else if ok && len(verifiedPermissions) == 0 {
			return true, message
		} else {
			n := input.Operation
			if input.Operation == "" {
				n = input.Method
			}
			return false, fmt.Sprintf("You are not authorized to access %s", n)
		}
	}

	return true, message
}

func optimizeReferer(link string) string {
	u, err := url.Parse(link)
	if err != nil {
		return ""
	}
	link = u.Path

	m1 := regexp.MustCompile(`/(dashboard)?/?(.*)`)
	m2 := regexp.MustCompile(`([a-zA-Z]+-)?[0-9]+`)
	m3 := regexp.MustCompile(`/$`)

	link = m1.ReplaceAllString(link, "$2")
	link = m2.ReplaceAllLiteralString(link, "$id")
	link = m3.ReplaceAllLiteralString(link, "")

	return link
}
