package postgres

type Project struct {
	Id        uint64             `gorm:"primarykey,column:id"`
	Name      string             `gorm:"column:name"`
	CompanyId uint64             `gorm:"column:company_id"`
	Settings  []*ProjectSettings `gorm:"foreignKey:ProjectId;references:Id"`
}

func (*Project) TableName() string {
	return "projects"
}
