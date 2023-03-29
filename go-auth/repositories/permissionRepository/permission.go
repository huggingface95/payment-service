package permissionRepository

import (
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
)

func GetUserPermissions(user postgres.User) []postgres.Permission {
	var m string
	var permissions []postgres.Permission

	query := database.PostgresInstance

	if user.StructName() == constants.StructMember {
		m = constants.ModelMember
	} else {
		m = constants.ModelIndividual
	}

	err := query.
		Joins("JOIN role_has_permissions on role_has_permissions.permission_id=permissions.id").
		Joins("JOIN roles on roles.id=role_has_permissions.role_id").
		Joins("JOIN group_role on group_role.role_id=roles.id").
		Joins("JOIN group_role_members_individuals on group_role_members_individuals.group_role_id=group_role.id").
		Where("group_role_members_individuals.user_id = ?", user.GetId()).
		Where("group_role_members_individuals.user_type = ?", m).
		Find(&permissions).Error
	if err != nil {
		return nil
	}

	return permissions
}

func IsGlobalOperation(name string) bool {
	var operation *postgres.PermissionOperation

	query := database.PostgresInstance

	rec := query.
		Model(&postgres.PermissionOperation{}).
		Preload("BindPermissions").
		Preload("ParentPermissions").
		Where("referer IS NULL").
		Where("name = ?", name).
		First(&operation)

	if rec.RowsAffected == 0 || operation == nil || len(operation.BindPermissions) > 0 || len(operation.ParentPermissions) > 0 {
		return false
	}

	return true
}

func GetStandardOperation(name string, referer string) (operation *postgres.PermissionOperation) {
	query := database.PostgresInstance

	rec := query.
		Model(&postgres.PermissionOperation{}).
		Preload("BindPermissions").
		Preload("ParentPermissions").
		Where("referer = ?", referer).
		Where("name = ?", name).
		First(&operation)

	if rec.RowsAffected == 0 {
		return nil
	}

	return
}