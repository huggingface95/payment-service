package requests

type ResetPasswordRequest struct {
	Email string `json:"email" binding:"required,email"`
	Type  string `json:"client_type"`
}

type ChangePasswordRequest struct {
	PasswordResetToken string `json:"password_reset_token" binding:"required"`
	Password           string `json:"password" binding:"required"`
	PasswordRepeat     string `json:"password_repeat" binding:"required"`
}
