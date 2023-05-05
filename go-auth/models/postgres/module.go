package postgres

type Module struct {
	Id   uint64 `gorm:"primarykey,column:id"`
	Name string `gorm:"column:name"`
}

func (*Module) TableName() string {
	return "modules"
}
