package individual

type RegisterRequest struct {
	FirstName      string `form:"first_name" binding:"required"`
	LastName       string `form:"last_name" binding:"required"`
	Phone          string `form:"phone" binding:"required,e164"`
	Email          string `form:"email" binding:"required,email"`
	CountryId      uint64 `form:"country_id" binding:"required"`
	Password       string `form:"password" binding:"required"`
	PasswordRepeat string `form:"password_repeat" binding:"required"`
}
