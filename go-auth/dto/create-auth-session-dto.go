package dto

type CreateAuthSessionDto struct {
	Provider string
	Email    string
	Company  string
}

func (cr *CreateAuthSessionDto) Parse(p string, e string, c string) *CreateAuthSessionDto {
	cr.Provider = p
	cr.Email = e
	cr.Company = c
	return cr
}
