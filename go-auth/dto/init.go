package dto

import "github.com/gamebtc/devicedetector"

var DTO = Dto{}

type Dto struct {
	DeviceDetectorInfo   *DeviceDetectorInfo
	CreateAuthLogDto     *CreateAuthLogDto
	CreateAuthSessionDto *CreateAuthSessionDto
}

func (d *Dto) Init() *Dto {
	d.DeviceDetectorInfo = NewDeviceDetectorInfo()
	d.CreateAuthLogDto = NewCreateAuthLogDto()
	d.CreateAuthSessionDto = NewCreateAuthSessionDto()
	return d
}

func NewDeviceDetectorInfo() *DeviceDetectorInfo {
	dd, err := devicedetector.NewDeviceDetector("regexes")
	if err != nil {
		return nil
	}

	return &DeviceDetectorInfo{
		detector: dd,
	}
}

func NewCreateAuthLogDto() *CreateAuthLogDto {
	return &CreateAuthLogDto{}
}

func NewCreateAuthSessionDto() *CreateAuthSessionDto {
	return &CreateAuthSessionDto{}
}