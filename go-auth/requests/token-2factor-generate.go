package requests

import "jwt-authentication-golang/models/postgres"

type GenerateTwoFactorQrRequest struct {
	MemberId    uint64 `json:"member_id"`
	TwoFaToken  string `json:"2fa_token"`
	AccessToken string `json:"access_token"`
	Type        string `json:"client_type"`
}

type ActivateTwoFactorQrRequest struct {
	Code        string `json:"code" binding:"required"`
	Secret      string `json:"secret" binding:"required"`
	AccessToken string `json:"access_token"`
	TwoFaToken  string `json:"2fa_token"`
	MemberId    uint64 `json:"member_id"`
	Type        string `json:"client_type"`
}

type VerifyTwoFactorQrRequest struct {
	Code        string                 `json:"code" binding:"required"`
	AccessToken string                 `json:"access_token"`
	TwoFaToken  string                 `json:"2fa_token"`
	MemberId    uint64                 `json:"member_id"`
	BackupCodes []postgres.BackupCodes `json:"backup_codes" binding:"required"`
	Type        string                 `json:"client_type"`
}

type DisableTwoFactorQrRequest struct {
	Code     string `json:"code"`
	MemberId uint64 `json:"member_id"`
}
