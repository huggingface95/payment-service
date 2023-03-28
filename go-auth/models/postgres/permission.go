package postgres

type Permission struct {
	Id          uint64 `gorm:"primarykey,column:id"`
	Name        string `gorm:"column:name"`
	DisplayName string `gorm:"column:display_name"`
}

func (*Permission) TableName() string {
	return "permissions"
}
