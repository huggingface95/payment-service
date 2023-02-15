package postgres

type GroupRole struct {
	Id     uint64 `gorm:"primarykey,column:id"`
	RoleId uint64 `gorm:"column:role_id"`
}

func (*GroupRole) TableName() string {
	return "group_role"
}
