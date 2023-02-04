package timelineRepository

import (
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/models/postgres"
)

func InsertTimeLineLog(dto *dto.CreateTimeLineDto) *postgres.KycTimeLine {

	kycTimeLine := &postgres.KycTimeLine{
		Browser:     dto.Browser,
		Ip:          dto.Ip,
		Os:          dto.Os,
		Tag:         dto.Tag,
		Action:      dto.Action,
		ActionType:  dto.ActionType,
		CompanyId:   dto.CompanyId,
		ApplicantId: dto.ApplicantId,
	}

	result := database.PostgresInstance.Omit("created_at", "applicant_type", "document_id", "creator_id").Create(&kycTimeLine)
	if result.Error != nil {
		return nil
	}

	return kycTimeLine
}
