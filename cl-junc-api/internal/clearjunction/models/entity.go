package models

type Corporate struct {
	Name                 string  `json:"name"`
	Address              Address `json:"address"`
	RegistrationNumber   string  `json:"registrationNumber,omitempty"`
	IncorporationCountry string  `json:"incorporationCountry,omitempty"`
	IncorporationDate    string  `json:"incorporationDate,omitempty"`
	Email                string  `json:"email,omitempty"`
}

type Individual struct {
	LastName   string   `json:"lastName"`
	FirstName  string   `json:"firstName"`
	MiddleName string   `json:"middleName,omitempty"`
	BirthDate  string   `json:"birthDate,omitempty"`
	BirthPlace string   `json:"birthPlace,omitempty"`
	Phone      string   `json:"phone,omitempty"`
	Email      string   `json:"email,omitempty"`
	Address    Address  `json:"address"`
	Document   Document `json:"document"`

	Inn       string `json:"inn,omitempty"`       // RU
	TaxNumber string `json:"taxNumber,omitempty"` // MD
}
