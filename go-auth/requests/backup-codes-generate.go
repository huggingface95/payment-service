package requests

import (
	"jwt-authentication-golang/models/postgres"
)

type GenerateBackupCodesRequest struct {
	MemberId    uint64 `form:"member_id"`
	AuthToken   string `form:"auth_token"`
	AccessToken string `form:"access_token"`
	Type        string `form:"type" binding:"required"`
}

type StoreBackupCodesRequest struct {
	MemberId    uint64               `form:"member_id"`
	AuthToken   string               `form:"auth_token"`
	AccessToken string               `form:"access_token"`
	BackupCodes *postgres.BackupJson `form:"backup_codes" binding:"required"`
	Type        string               `form:"type" binding:"required"`
}
