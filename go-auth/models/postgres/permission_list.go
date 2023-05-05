package postgres

type PermissionList struct {
	Id                 uint64             `gorm:"primarykey,column:id"`
	Name               string             `gorm:"column:name"`
	PermissionGroupId  uint64             `gorm:"column:permission_group_id"`
	PermissionCategory PermissionCategory `gorm:"foreignKey:PermissionGroupId"`
}

func (*PermissionList) TableName() string {
	return "permissions_list"
}
