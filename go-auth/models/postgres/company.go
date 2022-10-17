package postgres

type Company struct {
	Id   uint64 `gorm:"primarykey,column:id"`
	Name string `gorm:"column:name"`
}

func (*Company) TableName() string {
	return "companies"
}
