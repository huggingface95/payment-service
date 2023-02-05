package postgres

import "time"

type KycTimeLine struct {
	ID            uint64    `gorm:"primarykey,column:id"`
	CreatorId     uint64    `gorm:"column:creator_id"`
	Os            string    `gorm:"column:os"`
	Browser       string    `gorm:"column:browser"`
	Ip            string    `gorm:"column:ip"`
	Tag           string    `gorm:"column:tag"`
	Action        string    `gorm:"column:action"`
	ActionType    string    `gorm:"column:action_type"`
	DocumentId    uint64    `gorm:"column:document_id"`
	CompanyId     uint64    `gorm:"column:company_id"`
	ApplicantId   uint64    `gorm:"column:applicant_id"`
	ApplicantType string    `gorm:"column:applicant_type"`
	CreatedAt     time.Time `gorm:"column:created_at"`
}

func (*KycTimeLine) TableName() string {
	return "kyc_timeline"
}
