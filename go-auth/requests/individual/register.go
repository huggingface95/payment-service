package individual

type RegisterRequest struct {
	FirstName      string `json:"first_name" binding:"required"`
	LastName       string `json:"last_name" binding:"required"`
	Phone          string `json:"phone" binding:"required,e164"`
	Email          string `json:"email" binding:"required,email"`
	CountryId      uint64 `json:"country_id" binding:"required"`
	Password       string `json:"password" binding:"required"`
	PasswordRepeat string `json:"password_confirmation" binding:"required"`
	ClientType     string `json:"client_type" binding:"required,oneof=Corporate Private"`
	CompanyName    string `json:"company_name"`
	Url            string `json:"url"`
}
