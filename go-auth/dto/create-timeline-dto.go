package dto

import (
	"encoding/json"
	"jwt-authentication-golang/constants"
)

type CreateTimeLineDto struct {
	Action        string
	ActionType    string
	Tag           string
	CompanyId     uint64
	ApplicantId   uint64
	ApplicantType string
	Browser       string
	Os            string
	Ip            string
}

func (tl *CreateTimeLineDto) Parse(a string, at string, t string, cId uint64, aId uint64, device *DeviceDetectorInfo) *CreateTimeLineDto {
	tl.Action = a
	tl.ActionType = at
	tl.Tag = t
	tl.CompanyId = cId
	tl.ApplicantId = aId
	tl.Browser = device.ClientEngine
	tl.Os = device.OsName
	tl.Ip = device.Ip
	tl.ApplicantType = constants.ModelIndividual

	return tl
}

// MarshalBinary -
func (e *CreateTimeLineDto) MarshalBinary() ([]byte, error) {
	return json.Marshal(e)
}

// UnmarshalBinary -
func (e *CreateTimeLineDto) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &e); err != nil {
		return err
	}

	return nil
}
