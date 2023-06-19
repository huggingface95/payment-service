package individual

type LoginRequest struct {
	Email     string `json:"email" binding:"required,email"`
	Password  string `json:"password" binding:"required"`
	CompanyId uint64 `json:"company_id" binding:"required"`
	ProjectId uint64 `json:"project_id" binding:"required"`
}
