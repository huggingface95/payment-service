package models

type PayInInvoiceRequest struct {
	PayInPayoutRequest
	ProductName string `json:"productName"`
	SiteAddress string `json:"siteAddress"`
	Label       string `json:"label"`
	SuccessUrl  string `json:"successUrl"`
	FailUrl     string `json:"failUrl"`
	PostbackUrl string `json:"postbackUrl"`
}

func NewPayInInvoiceRequest(payInPayout PayInPayoutRequest, baseUrl string) PayInInvoiceRequest {

	return PayInInvoiceRequest{
		PayInPayoutRequest: payInPayout,
		ProductName:        "Product1",
		SuccessUrl:         baseUrl + "/clearjunction/postback",
		FailUrl:            baseUrl + "/clearjunction/postback",
		PostbackUrl:        baseUrl + "/clearjunction/postback",
	}
}
