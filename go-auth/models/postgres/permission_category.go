package postgres

type PermissionCategory struct {
	Id       uint64 `gorm:"primarykey,column:id"`
	Name     string `gorm:"column:name"`
	IsActive bool   `gorm:"column:is_active"`
}

func (*PermissionCategory) TableName() string {
	return "permission_category"
}

func (c *PermissionCategory) CheckActivityModule(module *Module) bool {
	switch c.Id {
	case 1:
		return module.Id == 1
	case 6, 7, 8, 9:
		return module.Id == 2
	default:
		return true
	}
}
