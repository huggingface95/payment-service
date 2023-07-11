package postgres

type PermissionUserLimitation struct {
	Id           uint64     `gorm:"primarykey,column:id"`
	PermissionId uint64     `gorm:"column:permission_id"`
	Permission   Permission `gorm:"foreignKey:PermissionId"`
	UserId       uint64     `gorm:"column:user_id"`
	UserType     string     `gorm:"column:user_type"`
}

func (*PermissionUserLimitation) TableName() string {
	return "permission_user_limitations"
}
