package models

type PayoutExecutionRequest struct {
	PayInPayoutRequest
	PostbackUrl string `json:"postbackUrl"`
	CustomInfo  struct {
		MyExampleParam1  string `json:"MyExampleParam1"`
		MyExampleObject1 struct {
			MyExampleParam2 string `json:"MyExampleParam2"`
			MyExampleParam3 string `json:"MyExampleParam3"`
		} `json:"MyExampleObject1"`
	} `json:"customInfo"`
	PayeeRequisite PayoutPayeeRequisite `json:"payeeRequisite"`
	PayerRequisite PayoutPayerRequisite `json:"payerRequisite"`
}

type PayoutPayeeRequisite struct {
	BankAccountNumber       string                        `json:"bankAccountNumber"`
	BankName                string                        `json:"bankName"`
	BankSwiftCode           string                        `json:"bankSwiftCode"`
	IntermediaryInstitution PayoutIntermediaryInstitution `json:"intermediaryInstitution"`
}

type PayoutPayerRequisite struct {
	BankAccountNumber       string                        `json:"bankAccountNumber"`
	BankName                string                        `json:"bankName"`
	BankSwiftCode           string                        `json:"bankSwiftCode"`
	IntermediaryInstitution PayoutIntermediaryInstitution `json:"intermediaryInstitution"`
}

type PayoutIntermediaryInstitution struct {
	BankCode string `json:"bankCode"`
	BankName string `json:"bankName"`
}

type PayoutExecutionResponse struct {
	PayInPayoutResponse
	CustomFormat PayInPayoutRequestCustomInfo `json:"customFormat"`
	Status       string                       `json:"status"`
	CheckOnly    bool                         `json:"checkOnly"`
}

func NewPayoutExecutionRequest(payInPayout PayInPayoutRequest, baseUrl string) PayoutExecutionRequest {

	return PayoutExecutionRequest{
		PayInPayoutRequest: payInPayout,
		PostbackUrl:        baseUrl + "/payin/postback",
	}
}
