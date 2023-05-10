package postgres

import (
	"jwt-authentication-golang/constants"
)

type ApplicantModuleActivity struct {
	Id            uint64  `gorm:"primarykey,column:id"`
	ApplicantId   uint64  `gorm:"column:applicant_id"`
	ApplicantType string  `gorm:"column:applicant_type"`
	ModuleId      uint64  `gorm:"column:module_id"`
	IsActive      bool    `gorm:"column:is_active"`
	Module        *Module `gorm:"foreignKey:ModuleId"`
}

func (*ApplicantModuleActivity) TableName() string {
	return "applicant_module_activity"
}

func (a *ApplicantModuleActivity) getType() string {
	switch a.ApplicantType {
	case "ApplicantCompany":
		return constants.ModuleActivityCorporate
	default:
		return constants.ModuleActivityIndividual
	}
}
