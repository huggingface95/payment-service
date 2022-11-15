package individual

type RegisterRequest struct {
	FirstName      string `json:"first_name" binding:"required"`
	LastName       string `json:"last_name" binding:"required"`
	Phone          string `json:"phone" binding:"required,e164"`
	Email          string `json:"email" binding:"required,email"`
	CountryId      uint64 `json:"country_id" binding:"required"`
	Password       string `json:"password" binding:"required"`
	PasswordRepeat string `json:"password_repeat" binding:"required"`
}
