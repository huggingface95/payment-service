package postgres

type GroupRole struct {
	Id             uint64          `gorm:"primarykey,column:id"`
	RoleId         uint64          `gorm:"column:role_id"`
	CompanySetting *CompanySetting `gorm:"foreignKey:ApplicantGroupRoleId"`
}

func (*GroupRole) TableName() string {
	return "group_role"
}
