package models

import (
	"cl-junc-api/internal/db"
	"strconv"
)

type StatusIban string

const (
	Accepted  StatusIban = "accepted"
	Pending              = "pending"
	Allocated            = "allocated"
	Declined             = "declined"
	Created              = "created"
)

type IbanCreateRequest struct {
	ClientOrder string     `json:"clientOrder"`
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

func NewIbanRequest(account *db.Account, wallet string, baseUrl string) IbanCreateRequest {
	return IbanCreateRequest{
		ClientOrder: account.AccountNumber,
		WalletUUID:  wallet,
		PostbackURL: baseUrl + "/postback/iban",
		Registrant: Registrant{
			ClientCustomerID: strconv.FormatUint(account.Id, 10),
			Individual:       PayInPayoutRequestPayeePayerIndividual{},
		},
	}
}
