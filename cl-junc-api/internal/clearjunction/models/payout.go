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
}

type PayoutIntermediaryInstitution struct {
	BankCode string `json:"bankCode"`
	BankName string `json:"bankName"`
}

type PayoutApproveResponse struct {
	RequestReference string          `json:"requestReference"`
	ActionResult     []ApproveResult `json:"actionResult"`
}

type ApproveResult struct {
	OrderReference         string    `json:"orderReference"`
	ActionProcessingStatus string    `json:"actionProcessingStatus"`
	Messages               []Message `json:"messages"`
}

func NewPayoutExecutionRequest(payInPayout PayInPayoutRequest, baseUrl string) PayoutExecutionRequest {

	return PayoutExecutionRequest{
		PayInPayoutRequest: payInPayout,
		PostbackUrl:        baseUrl + "/clearjunction/postback",
	}
}
