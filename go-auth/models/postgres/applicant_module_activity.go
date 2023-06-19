package postgres

import (
	"jwt-authentication-golang/constants"
)

type ApplicantModuleActivity struct {
	Id          uint64  `gorm:"primarykey,column:id"`
	ApplicantId uint64  `gorm:"column:applicant_id"`
	Individual  bool    `gorm:"column:individual"`
	Corporate   bool    `gorm:"column:corporate"`
	ModuleId    uint64  `gorm:"column:module_id"`
	Module      *Module `gorm:"foreignKey:ModuleId"`
}

func (*ApplicantModuleActivity) TableName() string {
	return "applicant_module_activity"
}

func (a *ApplicantModuleActivity) getType() string {
	if a.Corporate {
		return constants.Corporate
	}
	return constants.Individual
}
