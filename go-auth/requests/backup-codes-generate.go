package requests

import (
	"jwt-authentication-golang/models/postgres"
)

type GenerateBackupCodesRequest struct {
	MemberId    uint64 `json:"member_id"`
	AuthToken   string `json:"auth_token"`
	AccessToken string `json:"access_token"`
	Type        string `json:"type"`
}

type StoreBackupCodesRequest struct {
	MemberId    uint64               `json:"member_id"`
	AuthToken   string               `json:"auth_token"`
	AccessToken string               `json:"access_token"`
	BackupCodes *postgres.BackupJson `json:"backup_codes" binding:"required"`
	Type        string               `json:"type"`
}
