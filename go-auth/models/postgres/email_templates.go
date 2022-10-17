package postgres

import "time"

type EmailTemplate struct {
	Id          uint64 `gorm:"primarykey,column:id"`
	Name        string `gorm:"column:name"`
	Type        string `gorm:"column:type"`
	ServiceType string `gorm:"column:service_type"`
	UseLayout   bool   `gorm:"column:use_layout"`
	Subject     string `gorm:"column:subject"`
	Content     string `gorm:"column:content"`
	MemberId    uint64 `gorm:"column:member_id"`
	CompanyId   uint64 `gorm:"column:company_id"`

	CreatedAt time.Time
}

func (*EmailTemplate) TableName() string {
	return "email_templates"
}
