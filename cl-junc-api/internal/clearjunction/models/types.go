package models

import "errors"

type DocumentType string

var DOC_TYPES = []DocumentType{Passport, DriverLicense, CardId, Other}

const (
	Passport      DocumentType = "passport"
	DriverLicense              = "driverLicense"
	CardId                     = "idCard"
	Other                      = "other"
)

type Response struct {
	Errors []Message `json:"errors"`
}

func (r *Response) HasErrors() bool {
	return len(r.Errors) > 0
}

func (r *Response) ErrorFirstMessage() string {
	if r.HasErrors() {
		return r.Errors[0].Message
	}
	return ""
}

func (r *Response) ErrorFirst() error {
	if r.HasErrors() {
		return errors.New(r.Errors[0].Message)
	}
	return nil
}

type Message struct {
	Code    string `json:"code"`
	Message string `json:"message"`
	Details string `json:"details"`
}

type Address struct {
	Country string `json:"country"`
	State   string `json:"state,omitempty"`
	Zip     string `json:"zip"`
	City    string `json:"city"`
	Street  string `json:"street"`
}

type Document struct {
	Type              DocumentType `json:"type"`
	Number            string       `json:"number"`
	IssuedCountryCode string       `json:"issuedCountryCode"`
	IssuedBy          string       `json:"issuedBy"`
	IssuedDate        string       `json:"issuedDate"`
	ExpirationDate    string       `json:"expirationDate,omitempty"`
}
