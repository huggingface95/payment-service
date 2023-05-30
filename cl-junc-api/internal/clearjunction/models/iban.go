package models

import (
	"cl-junc-api/internal/db"
	"strconv"
	"time"
)

const (
	Accepted  = "accepted"
	Pending   = "pending"
	Allocated = "allocated"
	Declined  = "declined"
	Created   = "created"
)

type IbanCreateRequest struct {
	ClientOrder int64      `json:"clientOrder"`
	PostbackURL string     `json:"postbackUrl"`
	WalletUUID  string     `json:"walletUuid"`
	IbansGroup  string     `json:"ibansGroup"`
	IbanCountry string     `json:"ibanCountry"`
	Registrant  Registrant `json:"registrant"`
	CustomInfo  struct {
		AccountId uint64 `json:"accountId"`
	} `json:"customInfo"`
}

type Registrant struct {
	ClientCustomerID string                                 `json:"clientCustomerId"`
	Individual       PayInPayoutRequestPayeePayerIndividual `json:"individual"`
}

type IbanCreateResponse struct {
	Response
	ClientOrder      string `json:"clientOrder"`
	RequestReference string `json:"requestReference"`
	OrderReference   string `json:"orderReference"`
}

type IbanPostback struct {
	PayInPayoutPostback
	Iban string `json:"iban"`
}

type IbanStatusResponse struct {
	IbanPostback
	RequestReference string `json:"requestReference"`
}

type CheckRequisiteResponse struct {
	Response
	RequestReference  string `json:"requestReference"`
	BankSwiftCode     string `json:"bankSwiftCode"`
	BankName          string `json:"bankName"`
	SepaReachable     bool   `json:"sepaReachable"`
	SepaInstReachable bool   `json:"sepaInstReachable"`
}

type IbanStatusWithCustomerIdResponse struct {
	RequestReference string   `json:"requestReference"`
	ClientCustomerId string   `json:"clientCustomerId"`
	Ibans            []string `json:"ibans"`
}

func NewIbanRequest(account *db.Account, wallet string, baseUrl string) IbanCreateRequest {
	return IbanCreateRequest{
		ClientOrder: time.Now().Unix(),
		WalletUUID:  wallet,
		PostbackURL: baseUrl + "/clearjunction/iban/postback",
		IbanCountry: "GB",
		Registrant: Registrant{
			ClientCustomerID: strconv.FormatUint(account.Id, 10),
			Individual: PayInPayoutRequestPayeePayerIndividual{
				Email:     account.Payee.Email,
				Phone:     account.Payee.Phone,
				LastName:  account.Payee.LastName,
				FirstName: account.Payee.FirstName,
				BirthDate: "2017-01-01",
				Document: Document{
					IssuedDate:        "15-26-2017",
					Type:              "passport",
					Number:            "45454",
					IssuedCountryCode: "GB",
					IssuedBy:          "01-01-2020",
					ExpirationDate:    "15-26-2017",
				},
				Address: Address{
					Country: "GB",
					Zip:     account.Payee.Zip,
					State:   account.Payee.State,
					City:    account.Payee.City,
					Street:  "sheram",
				},
			},
		},
	}
}
