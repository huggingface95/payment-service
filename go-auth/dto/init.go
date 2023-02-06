package dto

import "github.com/gamebtc/devicedetector"

var DTO = Dto{}

type Dto struct {
	DeviceDetectorInfo   *DeviceDetectorInfo
	CreateAuthLogDto     *CreateAuthLogDto
	CreateAuthSessionDto *CreateAuthSessionDto
	CreateTimeLineDto    *CreateTimeLineDto
}

func (d *Dto) Init() *Dto {
	d.DeviceDetectorInfo = NewDeviceDetectorInfo()
	d.CreateAuthLogDto = NewCreateAuthLogDto()
	d.CreateAuthSessionDto = NewCreateAuthSessionDto()
	d.CreateTimeLineDto = NewCreateTimeLineDto()
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

func NewCreateTimeLineDto() *CreateTimeLineDto {
	return &CreateTimeLineDto{}
}
