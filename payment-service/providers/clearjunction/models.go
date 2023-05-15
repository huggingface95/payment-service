package clearjunction

import "net/http"

type ClearJunction struct {
	APIKey     string
	Password   string
	BaseURL    string
	httpClient *http.Client
}

// IBANRequest represents a request to create an IBAN.
type IBANRequest struct {
	ClientCustomerId string `json:"clientCustomerId"`
}

// IBANResponse represents a request to create an IBAN.
type IBANResponse struct {
	RequestReference string   `json:"requestReference"`
	ClientCustomerId string   `json:"clientCustomerId"`
	Ibans            []string `json:"ibans"`
}

func (r IBANResponse) GetIBANs() []string {
	return r.Ibans
}
