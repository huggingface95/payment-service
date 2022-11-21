package requests

type GenerateTwoFactorQrRequest struct {
	MemberId   uint64 `json:"member_id"`
	TwoFaToken string `json:"2fa_token"`
	AuthToken  string `json:"auth_token"`
	Type       string `json:"client_type" binding:"required"`
}

type ActivateTwoFactorQrRequest struct {
	Code       string `json:"code" binding:"required"`
	Secret     string `json:"secret" binding:"required"`
	AuthToken  string `json:"auth_token"`
	TwoFaToken string `json:"2fa_token"`
	MemberId   uint64 `json:"member_id"`
	Type       string `json:"client_type" binding:"required"`
}

type VerifyTwoFactorQrRequest struct {
	Code       string `json:"code" binding:"required"`
	AuthToken  string `json:"auth_token"`
	TwoFaToken string `json:"2fa_token"`
	MemberId   uint64 `json:"member_id"`
	BackupCode string `json:"backup_code"`
	Type       string `json:"client_type" binding:"required"`
}

type DisableTwoFactorQrRequest struct {
	Code      string `json:"code" binding:"required"`
	AuthToken string `json:"auth_token" binding:"required"`
	MemberId  uint64 `json:"member_id"`
	Type      string `json:"client_type" binding:"required"`
}
