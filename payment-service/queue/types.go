package queue

import "encoding/json"

type Task struct {
	Type     string          `json:"type"`
	Payload  json.RawMessage `json:"payload"`
	Provider string          `json:"provider"`
}

type IBANPayload struct {
	ClientOrder string `json:"clientOrder"`
	PostbackURL string `json:"postbackUrl"`
	WalletUUID  string `json:"walletUuid"`
	IbansGroup  string `json:"ibansGroup"`
	IbanCountry string `json:"ibanCountry"`
	Registrant  struct {
		ClientCustomerId string `json:"clientCustomerId"`
		Individual       struct {
			Phone      string `json:"phone"`
			Email      string `json:"email"`
			BirthDate  string `json:"birthDate"`
			BirthPlace string `json:"birthPlace"`
			Address    struct {
				Country string `json:"country"`
				Zip     string `json:"zip"`
				City    string `json:"city"`
				Street  string `json:"street"`
			} `json:"address"`
			Document struct {
				Type              string `json:"type"`
				Number            string `json:"number"`
				IssuedCountryCode string `json:"issuedCountryCode"`
				IssuedBy          string `json:"issuedBy"`
				IssuedDate        string `json:"issuedDate"`
				ExpirationDate    string `json:"expirationDate"`
			} `json:"document"`
			LastName   string `json:"lastName"`
			FirstName  string `json:"firstName"`
			MiddleName string `json:"middleName"`
		} `json:"individual"`
	} `json:"registrant"`
	CustomInfo struct {
		MyExampleParam1  string `json:"MyExampleParam1"`
		MyExampleObject1 struct {
			MyExampleParam2 string `json:"MyExampleParam2"`
			MyExampleParam3 string `json:"MyExampleParam3"`
		} `json:"MyExampleObject1"`
	} `json:"customInfo"`
}

type PayInPayload struct {
	// Определите поля структуры для пакета данных PayIn.
}

type PayOutPayload struct {
	// Определите поля структуры для пакета данных PayOut.
}

type EmailPayload struct {
	ID      int64       `json:"id"`
	Status  string      `json:"status"`
	Message string      `json:"message"`
	Data    interface{} `json:"data"`
}
