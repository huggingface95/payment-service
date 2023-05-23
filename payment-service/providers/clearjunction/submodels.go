package clearjunction

type ResponseMessage struct {
	Code    string `json:"code"`
	Message string `json:"message"`
	Details string `json:"details"`
}

type RequestCustomInfo struct {
	PaymentId uint64 `json:"payment_id"`
}

type PostbackSubStatuses struct {
	OperStatus       string `json:"operStatus"`
	ComplianceStatus string `json:"complianceStatus"`
}

type PostbackPayeePayer struct {
	WalletUuid       string `json:"walletUuid"`
	ClientCustomerId string `json:"clientCustomerId"`
}

type Message struct {
	Code    string `json:"code"`
	Message string `json:"message"`
	Details string `json:"details"`
}
