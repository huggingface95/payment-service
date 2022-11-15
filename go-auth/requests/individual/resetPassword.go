package individual

type ResetPasswordRequest struct {
	Email string `json:"email" binding:"required,email"`
}

type ChangePasswordRequest struct {
	Token          string `json:"token" binding:"required"`
	Password       string `json:"password" binding:"required"`
	PasswordRepeat string `json:"password_repeat" binding:"required"`
}
