package postgres

type Permission struct {
	Id               uint64         `gorm:"primarykey,column:id"`
	PermissionListId uint64         `gorm:"column:permission_list_id"`
	Name             string         `gorm:"column:name"`
	DisplayName      string         `gorm:"column:display_name"`
	PermissionList   PermissionList `gorm:"foreignKey:PermissionListId"`
}

func (*Permission) TableName() string {
	return "permissions"
}
