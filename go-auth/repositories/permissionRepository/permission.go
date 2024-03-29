package permissionRepository

import (
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
)

func GetUserPermissions(user postgres.User) []postgres.Permission {
	var permissions []postgres.Permission
	var userType map[string]interface{}
	var userLType map[string]interface{}

	query := database.PostgresInstance

	if user.StructName() == constants.StructMember {
		userType = map[string]interface{}{"group_role_members_individuals.user_type": constants.ModelMember}
		userLType = map[string]interface{}{"user_type": constants.ModelMember, "user_id": user.GetId()}
	} else {
		userType = map[string]interface{}{"group_role_members_individuals.user_type": constants.ModelIndividual}
		userLType = map[string]interface{}{"user_type": constants.ModelIndividual, "user_id": user.GetId()}
	}

	err := query.
		Preload("PermissionList.PermissionCategory").
		Preload("Limitations", userLType).
		Preload("Limitations.Permission").
		Joins("JOIN role_has_permissions on role_has_permissions.permission_id=permissions.id").
		Joins("JOIN roles on roles.id=role_has_permissions.role_id").
		Joins("JOIN group_role on group_role.role_id=roles.id").
		Joins("JOIN group_role_members_individuals on group_role_members_individuals.group_role_id=group_role.id").
		Where("group_role_members_individuals.user_id = ?", user.GetId()).
		Where(userType).
		Find(&permissions).Error

	if err != nil {
		return nil
	}

	return permissions
}

func IsGlobalOperation(name string, method string, t string) bool {
	var operation *postgres.PermissionOperation

	query := database.PostgresInstance

	rec := query.
		Model(&postgres.PermissionOperation{}).
		Preload("BindPermissions").
		Preload("ParentPermissions").
		Where("referer IS NULL").
		Where("type = ?", t).
		Where(query.Where("name = ?", name).Or("method = ?", method)).
		First(&operation)

	if rec.RowsAffected == 0 || operation == nil || len(operation.BindPermissions) > 0 || len(operation.ParentPermissions) > 0 {
		return false
	}

	return true
}

func GetStandardOperation(name string, method string, t string, referer string) (operation *postgres.PermissionOperation) {
	query := database.PostgresInstance

	rec := query.
		Model(&postgres.PermissionOperation{}).
		Preload("BindPermissions.PermissionList.PermissionCategory").
		Preload("ParentPermissions.PermissionList.PermissionCategory").
		Where("referer = ?", referer).
		Where("type = ?", t).
		Where(query.Where("name = ?", name).Or("method = ?", method)).
		First(&operation)

	if rec.RowsAffected == 0 {
		return nil
	}

	return
}
