package requests

import (
	"jwt-authentication-golang/models/postgres"
)

type GenerateBackupCodesRequest struct {
	MemberId  uint64 `json:"member_id"`
	AuthToken string `json:"auth_token" binding:"required"`
	Type      string `json:"client_type"`
}

type StoreBackupCodesRequest struct {
	MemberId    uint64                 `json:"member_id"`
	AuthToken   string                 `json:"auth_token" binding:"required"`
	BackupCodes []postgres.BackupCodes `json:"backup_codes" binding:"required"`
	Type        string                 `json:"client_type"`
}
