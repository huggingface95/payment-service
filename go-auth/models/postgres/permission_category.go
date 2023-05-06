package postgres

type PermissionCategory struct {
	Id       uint64 `gorm:"primarykey,column:id"`
	Name     string `gorm:"column:name"`
	IsActive bool   `gorm:"column:is_active"`
}

func (*PermissionCategory) TableName() string {
	return "permission_category"
}
