package models

import "time"

type TransactionReportRequest struct {
	WalletUUID    string    `json:"walletUuid"`
	TimestampFrom time.Time `json:"timestampFrom"`
	TimestampTo   time.Time `json:"timestampTo"`
}
