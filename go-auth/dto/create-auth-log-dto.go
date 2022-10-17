package dto

type CreateAuthLogDto struct {
	Provider   string
	Email      string
	Company    string
	DeviceInfo *DeviceDetectorInfo
}

func (cr *CreateAuthLogDto) Parse(p string, e string, c string, d *DeviceDetectorInfo) *CreateAuthLogDto {
	cr.Provider = p
	cr.Email = e
	cr.Company = c
	cr.DeviceInfo = d
	return cr
}
