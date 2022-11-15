package requests

type GenerateTwoFactorQrRequest struct {
	MemberId    uint64 `json:"member_id"`
	TwoFaToken  string `json:"2fa_token"`
	AccessToken string `json:"access_token"`
	Type        string `json:"type"`
	Token       string `json:"token"`
}

type ActivateTwoFactorQrRequest struct {
	Code        string `json:"code" binding:"required"`
	Secret      string `json:"secret" binding:"required"`
	AccessToken string `json:"access_token"`
	AuthToken   string `json:"auth_token"`
	MemberId    uint64 `json:"member_id"`
	Type        string `json:"type"`
	Token       string `json:"token"`
}

type VerifyTwoFactorQrRequest struct {
	Code       string `json:"code" binding:"required"`
	AuthToken  string `json:"auth_token"`
	MemberId   uint64 `json:"member_id"`
	BackupCode string `json:"backup_code"`
	Type       string `json:"type"`
	Token      string `json:"token"`
}

type DisableTwoFactorQrRequest struct {
	Code     string `json:"code" binding:"required"`
	Password string `json:"password" binding:"required"`
	MemberId uint64 `json:"member_id"`
	Type     string `json:"type"`
	Token    string `json:"token"`
}
