package timelineRepository

import (
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/models/postgres"
	"time"
)

func InsertTimeLineLog(dto *dto.CreateTimeLineDto) *postgres.KycTimeLine {

	kycTimeLine := &postgres.KycTimeLine{
		Browser:       dto.Browser,
		Ip:            dto.Ip,
		Os:            dto.Os,
		Tag:           dto.Tag,
		Action:        dto.Action,
		ActionType:    dto.ActionType,
		CompanyId:     dto.CompanyId,
		ApplicantId:   dto.ApplicantId,
		ApplicantType: dto.ApplicantType,
		CreatedAt:     time.Now(),
	}

	result := database.PostgresInstance.Omit("document_id", "creator_id").Create(&kycTimeLine)
	if result.Error != nil {
		return nil
	}

	return kycTimeLine
}
