package postgres

type CompanyModule struct {
	Id        uint64  `gorm:"primarykey,column:id"`
	CompanyId uint64  `gorm:"column:company_id"`
	ModuleId  uint64  `gorm:"column:module_id"`
	IsActive  bool    `gorm:"column:is_active"`
	Module    *Module `gorm:"foreignKey:ModuleId"`
}

func (*CompanyModule) TableName() string {
	return "company_modules"
}
