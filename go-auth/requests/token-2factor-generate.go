package requests

type GenerateTwoFactorQrRequest struct {
	MemberId    uint64 `json:"member_id"`
	TwoFaToken  string `json:"2fa_token"`
	AccessToken string `json:"access_token"`
	Type        string `json:"client_type" binding:"required"`
}

type ActivateTwoFactorQrRequest struct {
	Code        string `json:"code" binding:"required"`
	Secret      string `json:"secret" binding:"required"`
	AccessToken string `json:"access_token"`
	TwoFaToken  string `json:"2fa_token"`
	MemberId    uint64 `json:"member_id"`
	Type        string `json:"client_type" binding:"required"`
}

type VerifyTwoFactorQrRequest struct {
	Code        string `json:"code" binding:"required"`
	AccessToken string `json:"access_token"`
	TwoFaToken  string `json:"2fa_token"`
	MemberId    uint64 `json:"member_id"`
	BackupCode  string `json:"backup_code"`
	Type        string `json:"client_type" binding:"required"`
}

type DisableTwoFactorQrRequest struct {
	Code        string `json:"code" binding:"required"`
	AccessToken string `json:"access_token" binding:"required"`
	MemberId    uint64 `json:"member_id"`
	Type        string `json:"client_type" binding:"required"`
}
