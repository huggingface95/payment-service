package clearjunction

// ResponseMessage представляет сообщение ответа с кодом, сообщением и деталями.
type ResponseMessage struct {
	Code    string `json:"code"`
	Message string `json:"message"`
	Details string `json:"details"`
}

// RequestCustomInfo содержит информацию о пользовательских данных запроса.
type RequestCustomInfo struct {
	PaymentId uint64 `json:"payment_id"`
}

// PostbackSubStatuses представляет дополнительные статусы для postback.
type PostbackSubStatuses struct {
	OperStatus       string `json:"operStatus"`
	ComplianceStatus string `json:"complianceStatus"`
}

// PostbackPayeePayer представляет информацию о получателе и отправителе платежа для postback.
type PostbackPayeePayer struct {
	WalletUuid       string `json:"walletUuid"`
	ClientCustomerId string `json:"clientCustomerId"`
}

// Message представляет сообщение с кодом, сообщением и деталями.
type Message struct {
	Code    string `json:"code"`
	Message string `json:"message"`
	Details string `json:"details"`
}

// PayRequestMessage представляет модель данных для сообщений в PayIn и PayOut postback.
type PayRequestMessage struct {
	Code    string `json:"code"`
	Message string `json:"message"`
	Details string `json:"details"`
}

// PayRequestCustomInfo представляет модель данных для пользовательской информации в PayIn и PayOut postback.
type PayRequestCustomInfo struct {
	PaymentId uint64 `json:"payment_id"`
}

// PayPostbackSubStatuses представляет модель данных для под-статусов в PayIn и PayOut postback.
type PayPostbackSubStatuses struct {
	OperStatus       string `json:"operStatus"`
	ComplianceStatus string `json:"complianceStatus"`
}

// PayPostbackPayeePayer представляет модель данных для получателя и плательщика в PayIn и PayOut postback.
type PayPostbackPayeePayer struct {
	WalletUuid       string `json:"walletUuid"`
	ClientCustomerId string `json:"clientCustomerId"`
}

// Registrant представляет информацию о заявителе IBAN.
type Registrant struct {
	ClientCustomerID string         `json:"clientCustomerId"`
	Individual       IndividualData `json:"individual,omitempty"`
	Corporate        CorporateData  `json:"corporate,omitempty"`
}

// IndividualData представляет данные об индивидуальном клиенте.
type IndividualData struct {
	Phone      string       `json:"phone"`
	Email      string       `json:"email"`
	BirthDate  string       `json:"birthDate"`
	BirthPlace string       `json:"birthPlace"`
	Address    AddressData  `json:"address"`
	Document   DocumentData `json:"document"`
	LastName   string       `json:"lastName"`
	FirstName  string       `json:"firstName"`
	MiddleName string       `json:"middleName"`
}

// CorporateData представляет данные о корпоративном клиенте.
type CorporateData struct {
	ClientCustomerID string        `json:"clientCustomerId"`
	CompanyName      string        `json:"companyName"`
	Address          AddressData   `json:"address"`
	Document         DocumentData  `json:"document"`
	ContactPerson    ContactPerson `json:"contactPerson"`
}

// AddressData представляет информацию об адресе.
type AddressData struct {
	Country string `json:"country"`
	Zip     string `json:"zip"`
	City    string `json:"city"`
	Street  string `json:"street"`
}

// DocumentData представляет информацию о документе.
type DocumentData struct {
	Type              string `json:"type"`
	Number            string `json:"number"`
	IssuedCountryCode string `json:"issuedCountryCode"`
	IssuedBy          string `json:"issuedBy"`
	IssuedDate        string `json:"issuedDate"`
	ExpirationDate    string `json:"expirationDate"`
}

// ContactPerson представляет информацию о контактном лице для корпоративного клиента.
type ContactPerson struct {
	LastName   string `json:"lastName"`
	FirstName  string `json:"firstName"`
	MiddleName string `json:"middleName"`
	Phone      string `json:"phone"`
	Email      string `json:"email"`
}
