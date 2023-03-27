package postgres

type Role struct {
	Id   uint64 `gorm:"primarykey,column:id"`
	Name string `gorm:"column:name"`
}

func (*Role) TableName() string {
	return "roles"
}
