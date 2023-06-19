package postgres

type PermissionOperation struct {
	Id                uint64       `gorm:"primarykey,column:id"`
	Name              string       `gorm:"column:name"`
	Referer           string       `gorm:"column:referer"`
	Hidden            bool         `gorm:"column:hidden"`
	Method            string       `gorm:"column:method"`
	Type              string       `gorm:"column:type"`
	BindPermissions   []Permission `gorm:"many2many:permission_operations_binds;foreignKey:Id;joinForeignKey:PermissionOperationsId;References:Id;joinReferences:PermissionId"`
	ParentPermissions []Permission `gorm:"many2many:permission_operations_parents;foreignKey:Id;joinForeignKey:PermissionOperationsId;References:Id;joinReferences:PermissionId"`
}

func (*PermissionOperation) TableName() string {
	return "permission_operations"
}
