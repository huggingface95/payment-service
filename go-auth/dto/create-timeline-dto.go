package dto

type CreateTimeLineDto struct {
	Action      string
	ActionType  string
	Tag         string
	CompanyId   uint64
	ApplicantId uint64
}

func (tl *CreateTimeLineDto) Parse(a string, at string, t string, cId uint64, aId uint64) *CreateTimeLineDto {
	tl.Action = a
	tl.ActionType = at
	tl.Tag = t
	tl.CompanyId = cId
	tl.ApplicantId = aId

	return tl
}
