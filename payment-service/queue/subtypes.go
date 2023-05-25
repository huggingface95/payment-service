package queue

type Payer struct {
	ClientCustomerID string             `json:"clientCustomerId"`
	WalletUUID       string             `json:"walletUuid"`
	Individual       IndividualRuEntity `json:"individual"`
}

type IndividualRuPayee struct {
	ClientCustomerID string             `json:"clientCustomerId"`
	WalletUUID       string             `json:"walletUuid"`
	Individual       IndividualRuEntity `json:"individual"`
}

type IndividualPayee struct {
	ClientCustomerId string          `json:"clientCustomerId"`
	WalletUUID       string          `json:"walletUuid"`
	Individual       IndividualEntry `json:"individual"`
}

type IndividualRuEntity struct {
	Phone      string   `json:"phone"`
	Email      string   `json:"email"`
	BirthDate  string   `json:"birthDate"`
	BirthPlace string   `json:"birthPlace"`
	Address    Address  `json:"address"`
	Document   Document `json:"document"`
	LastName   string   `json:"lastName"`
	FirstName  string   `json:"firstName"`
	MiddleName string   `json:"middleName"`
}

type IndividualEntry struct {
	Phone      string   `json:"phone"`
	Email      string   `json:"email"`
	BirthDate  string   `json:"birthDate"`
	BirthPlace string   `json:"birthPlace"`
	Address    Address  `json:"address"`
	Document   Document `json:"document"`
	LastName   string   `json:"lastName"`
	FirstName  string   `json:"firstName"`
	MiddleName string   `json:"middleName"`
}

type Address struct {
	Country string `json:"country"`
	Zip     string `json:"zip"`
	City    string `json:"city"`
	Street  string `json:"street"`
}

type Document struct {
	Type              string `json:"type"`
	Number            string `json:"number"`
	IssuedCountryCode string `json:"issuedCountryCode"`
	IssuedBy          string `json:"issuedBy"`
	IssuedDate        string `json:"issuedDate"`
	ExpirationDate    string `json:"expirationDate"`
}
type Registrant struct {
	ClientCustomerId string     `json:"clientCustomerId"`
	Individual       Individual `json:"individual"`
}

type Individual struct {
	Phone      string   `json:"phone"`
	Email      string   `json:"email"`
	BirthDate  string   `json:"birthDate"`
	BirthPlace string   `json:"birthPlace"`
	Address    Address  `json:"address"`
	Document   Document `json:"document"`
	LastName   string   `json:"lastName"`
	FirstName  string   `json:"firstName"`
	MiddleName string   `json:"middleName"`
}

type CustomInfo struct {
	MyExampleParam1  string          `json:"MyExampleParam1"`
	MyExampleObject1 MyExampleObject `json:"MyExampleObject1"`
}

type MyExampleObject struct {
	MyExampleParam2 string `json:"MyExampleParam2"`
	MyExampleParam3 string `json:"MyExampleParam3"`
}
