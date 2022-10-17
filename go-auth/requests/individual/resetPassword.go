package individual

type ResetPasswordRequest struct {
	Email string `form:"email" binding:"required,email"`
}

type ChangePasswordRequest struct {
	Token          string `form:"token" binding:"required"`
	Password       string `form:"password" binding:"required"`
	PasswordRepeat string `form:"password_repeat" binding:"required"`
}
