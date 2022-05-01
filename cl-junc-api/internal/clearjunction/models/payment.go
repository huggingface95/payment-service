package models

import (
	"cl-junc-api/internal/db"
	"encoding/json"
	"time"
)

type PayInPayoutRequest struct {
	ClientOrder int64                        `json:"clientOrder"`
	Currency    string                       `json:"currency"`
	Amount      float64                      `json:"amount"`
	Description string                       `json:"description"`
	Payer       PayInPayoutRequestPayer      `json:"payer"`
	Payee       PayInPayoutRequestPayee      `json:"payee"`
	CustomInfo  PayInPayoutRequestCustomInfo `json:"customInfo"`
}
type PayInPayoutRequestPayer struct {
	ClientCustomerId uint64                                 `json:"clientCustomerId"`
	WalletUuid       string                                 `json:"walletUuid"`
	Individual       PayInPayoutRequestPayeePayerIndividual `json:"individual"`
}
type PayInPayoutRequestPayee struct {
	ClientCustomerId uint64                                 `json:"clientCustomerId"`
	WalletUuid       string                                 `json:"walletUuid"`
	Individual       PayInPayoutRequestPayeePayerIndividual `json:"individual"`
}
type PayInPayoutRequestPayeePayerIndividual struct {
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
	Inn        string `json:"inn"`
}

type PayInPayoutRequestCustomInfo struct {
	PaymentId uint64 `json:"payment_id"`
}

type PayInPayoutResponse struct {
	RequestReference string                         `json:"requestReference"`
	OrderReference   string                         `json:"orderReference"`
	Status           string                         `json:"status"`
	CreatedAt        time.Time                      `json:"createdAt"`
	RedirectURL      string                         `json:"redirectURL"`
	CheckOnly        bool                           `json:"checkOnly"`
	Messages         []PayInPayoutResponseMessages  `json:"messages"`
	SubStatuses      PayInPayoutResponseSubStatuses `json:"subStatuses"`
	CustomFormat     PayInPayoutRequestCustomInfo   `json:"customFormat"`
}

type PayInPayoutResponseMessages struct {
	Code    string `json:"code"`
	Message string `json:"message"`
	Details string `json:"details"`
}

type PayInPayoutResponseSubStatuses struct {
	OperStatus       string `json:"operStatus"`
	ComplianceStatus string `json:"complianceStatus"`
}

type PaymentCommon interface{}

// MarshalBinary -
func (p *PayInPayoutResponse) MarshalBinary() ([]byte, error) {
	return json.Marshal(p)
}

// UnmarshalBinary -
func (p *PayInPayoutResponse) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &p); err != nil {
		return err
	}
	return nil
}

func NewPayInPayoutRequest(payment *db.Payment, payee *db.Payee, amount float64, currency string, wallet string) PayInPayoutRequest {
	return PayInPayoutRequest{
		ClientOrder: time.Now().Unix(),
		Amount:      amount,
		Currency:    currency,
		Payer: PayInPayoutRequestPayer{
			ClientCustomerId: payee.Id,
			WalletUuid:       wallet,
			Individual: PayInPayoutRequestPayeePayerIndividual{
				Email:     payee.Email,
				Phone:     payee.Phone,
				LastName:  payee.LastName,
				FirstName: payee.FirstName,
			},
		},
		Payee: PayInPayoutRequestPayee{
			ClientCustomerId: payment.Id,
			WalletUuid:       wallet,
			Individual: PayInPayoutRequestPayeePayerIndividual{
				Email:     payment.Email,
				Phone:     payment.Phone,
				LastName:  payment.Name,
				FirstName: payment.Name,
			},
		},
		CustomInfo: PayInPayoutRequestCustomInfo{PaymentId: payment.Id},
	}
}
