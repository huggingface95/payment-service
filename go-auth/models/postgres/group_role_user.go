package postgres

type GroupRoleUser struct {
	GroupRoleId uint64 `gorm:"column:group_role_id"`
	UserId      uint64 `gorm:"column:user_id"`
	UserType    string `gorm:"column:user_type"`
}

func (*GroupRoleUser) TableName() string {
	return "group_role_members_individuals"
}
