package models

import (
	"cl-junc-api/internal/db"
	"encoding/json"
	"time"
)

type PayInPayoutRequest struct {
	ClientOrder    int64                          `json:"clientOrder"`
	Currency       string                         `json:"currency"`
	Amount         float64                        `json:"amount"`
	Description    string                         `json:"description"`
	Payer          PayInPayoutRequestPayer        `json:"payer"`
	Payee          PayInPayoutRequestPayee        `json:"payee"`
	CustomInfo     PayInPayoutRequestCustomInfo   `json:"customInfo"`
	PayeeRequisite PayInPayoutPayeePayerRequisite `json:"payeeRequisite"`
	PayerRequisite PayInPayoutPayeePayerRequisite `json:"payerRequisite"`
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
	Phone      string   `json:"phone"`
	Email      string   `json:"email"`
	BirthDate  string   `json:"birthDate"`
	BirthPlace string   `json:"birthPlace"`
	Address    Address  `json:"address"`
	Document   Document `json:"document"`
	LastName   string   `json:"lastName"`
	FirstName  string   `json:"firstName"`
	MiddleName string   `json:"middleName"`
	Inn        string   `json:"inn"`
}
type PayInPayoutRequestCustomInfo struct {
	PaymentId uint64 `json:"payment_id"`
}
type PayInPayoutPayeePayerRequisite struct {
	SortCode      string `json:"sortCode"`
	AccountNumber string `json:"accountNumber"`
	Iban          string `json:"iban"`
	BankSwiftCode string `json:"bankSwiftCode"`
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

func NewPayInPayoutRequest(payment *db.Payment, amount float64, currency string, wallet string) PayInPayoutRequest {
	return PayInPayoutRequest{
		ClientOrder: time.Now().Unix(),
		Amount:      amount,
		Currency:    currency,
		Description: "Custom Description",
		Payer: PayInPayoutRequestPayer{
			ClientCustomerId: payment.Account.Payee.Id,
			WalletUuid:       wallet,

			Individual: PayInPayoutRequestPayeePayerIndividual{
				Email:     payment.Account.Payee.Email,
				Phone:     payment.Account.Payee.Phone,
				LastName:  payment.Account.Payee.LastName,
				FirstName: payment.Account.Payee.FirstName,
				Address: Address{
					Country: "AM",
					Zip:     "084",
					State:   "erevan",
					City:    "erevan",
					Street:  "sheram",
				},
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
		PayeeRequisite: PayInPayoutPayeePayerRequisite{
			Iban:          "HU93116000060000000012345676",
			SortCode:      "000000",
			AccountNumber: "12345676",
		},
	}
}
