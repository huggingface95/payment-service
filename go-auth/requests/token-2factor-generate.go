package requests

type GenerateTwoFactorQrRequest struct {
	MemberId    uint64 `form:"member_id"`
	TwoFaToken  string `form:"2fa_token"`
	AccessToken string `form:"access_token"`
	Type        string `form:"type"`
	Token       string `form:"token"`
}

type ActivateTwoFactorQrRequest struct {
	Code        string `form:"code" binding:"required"`
	Secret      string `form:"secret" binding:"required"`
	AccessToken string `form:"access_token"`
	AuthToken   string `form:"auth_token"`
	MemberId    uint64 `form:"member_id"`
	Type        string `form:"type"`
	Token       string `form:"token"`
}

type VerifyTwoFactorQrRequest struct {
	Code       string `form:"code" binding:"required"`
	AuthToken  string `form:"auth_token"`
	MemberId   uint64 `form:"member_id"`
	BackupCode string `form:"backup_code"`
	Type       string `form:"type"`
	Token      string `form:"token"`
}

type DisableTwoFactorQrRequest struct {
	Code     string `form:"code" binding:"required"`
	Password string `form:"password" binding:"required"`
	MemberId uint64 `form:"member_id"`
	Type     string `form:"type"`
	Token    string `form:"token"`
}
