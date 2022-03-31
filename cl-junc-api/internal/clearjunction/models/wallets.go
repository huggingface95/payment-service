package models

type CreateWalletRequest struct {
	ClientCustomerId   string     `json:"clientCustomerId"`
	RequiredRequisites []string   `json:"requiredRequisites"`
	Currency           string     `json:"currency"`
	Individual         Individual `json:"individual"`
	CustomInfo         CustomInfo `json:"customInfo"`
}

type CustomInfo struct {
	OriginalUID   string `json:"originalUID"`
	AccountId     uint64 `json:"accountId"`
	AccountNumber string `json:"account_number"`
	RequestId     uint64 `json:"request_id"`
}

type GetWalletResponse struct {
	OwnerUUID      string `json:"ownerUuid"`
	WalletUUID     string `json:"walletUuid"`
	PaymentMethods []struct {
		Type          string `json:"type"`
		BankCode      string `json:"bankCode"`
		AccountNumber string `json:"accountNumber"`
		Name          string `json:"name"`
	} `json:"paymentMethods"`
	Amounts []struct {
		CurrencyCode   string `json:"currencyCode"`
		AvailableFunds int    `json:"availableFunds"`
	} `json:"amounts"`
}
