package postgres

type ProjectSettings struct {
	Id            uint64     `gorm:"primarykey,column:id"`
	ProjectId     uint64     `gorm:"column:project_id"`
	GroupRoleId   uint64     `gorm:"column:group_role_id"`
	ApplicantType string     `gorm:"column:applicant_type"`
	SecretKey     string     `gorm:"column:secret_key"`
	Hash          string     `gorm:"column:hash"`
	GroupRole     *GroupRole `gorm:"foreignKey:GroupRoleId"`
	Project       *Project   `gorm:"foreignKey:ProjectId"`
}

func (*ProjectSettings) TableName() string {
	return "project_settings"
}
