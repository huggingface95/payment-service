package individual

const RegisterStandard = "RegisterRequest"
const RegisterPrivate = "RegisterRequestPrivate"
const RegisterCorporate = "RegisterRequestCorporate"

type RegisterApplicantInterface interface {
	GetType() string
	GetPassword() string
	GetPasswordRepeat() string
	GetEmail() string
	GetCompanyId() uint64
	GetCountryId() *uint64
	GetProjectId() uint64
	GetCompanyName() string
	GetUrl() string
}

type RegisterRequestApplicant struct {
	CompanyId uint64 `json:"company_id" binding:"required"`
	ProjectId uint64 `json:"project_id" binding:"required"`
	Url       string `json:"url" binding:"required"`
	FormType  string `json:"form_type" binding:"required,oneof=Form Button"`
	Email     string `json:"email" binding:"required,email"`
	Phone     string `json:"phone" binding:"required,e164"`
	CountryId uint64 `json:"country_id,omitempty"`
	Address   string `json:"address,omitempty"`
}

type RegisterRequest struct {
	FirstName      string `json:"first_name"`
	LastName       string `json:"last_name"`
	Phone          string `json:"phone" binding:"required,e164"`
	Email          string `json:"email" binding:"required,email"`
	CountryId      uint64 `json:"country_id,omitempty"`
	Password       string `json:"password" binding:"required"`
	PasswordRepeat string `json:"password_confirmation" binding:"required"`
	ClientType     string `json:"client_type" binding:"required,oneof=Corporate Private"`
	CompanyName    string `json:"company_name,omitempty"`
	Url            string `json:"url,omitempty"`
}

type RegisterRequestPrivate struct {
	RegisterRequestApplicant
	FirstName      string `json:"first_name" binding:"required"`
	LastName       string `json:"last_name" binding:"required"`
	Password       string `json:"password" binding:"required"`
	PasswordRepeat string `json:"password_confirmation" binding:"required"`
}

type RegisterRequestCorporate struct {
	RegisterRequestApplicant
	Name            string `json:"name" binding:"required"`
	IncorporateDate string `json:"incorporate_date,omitempty"`
	TaksId          uint64 `json:"taks_id,omitempty"`
	RegNumber       string `json:"reg_number,omitempty"`
	EntityType      string `json:"entity_type,omitempty"`
	BusinessType    string `json:"business_type,omitempty"`
	WebSite         string `json:"web_site,omitempty"`
}

func (r RegisterRequestPrivate) GetType() string {
	return RegisterPrivate
}

func (r RegisterRequestCorporate) GetType() string {
	return RegisterCorporate
}

func (r RegisterRequest) GetType() string {
	return RegisterStandard
}

func (r RegisterRequestPrivate) GetPassword() string {
	return r.Password
}

func (r RegisterRequestCorporate) GetPassword() string {
	return ""
}

func (r RegisterRequest) GetPassword() string {
	return r.Password
}

func (r RegisterRequestPrivate) GetPasswordRepeat() string {
	return r.PasswordRepeat
}

func (r RegisterRequestCorporate) GetPasswordRepeat() string {
	return ""
}

func (r RegisterRequest) GetPasswordRepeat() string {
	return r.PasswordRepeat
}

func (r RegisterRequestPrivate) GetEmail() string {
	return r.PasswordRepeat
}

func (r RegisterRequestCorporate) GetEmail() string {
	return ""
}

func (r RegisterRequest) GetEmail() string {
	return r.PasswordRepeat
}

func (r RegisterRequestPrivate) GetCountryId() *uint64 {
	if r.CountryId > 0 {
		return &r.CountryId
	}
	return nil
}
func (r RegisterRequestCorporate) GetCountryId() *uint64 {
	if r.CountryId > 0 {
		return &r.CountryId
	}
	return nil
}
func (r RegisterRequest) GetCountryId() *uint64 {
	if r.CountryId > 0 {
		return &r.CountryId
	}
	return nil
}

func (r RegisterRequestPrivate) GetCompanyId() uint64 {
	return r.CompanyId
}

func (r RegisterRequestCorporate) GetCompanyId() uint64 {
	return r.CompanyId
}

func (r RegisterRequest) GetCompanyId() uint64 {
	return 1
}

func (r RegisterRequestPrivate) GetProjectId() uint64 {
	return r.ProjectId
}

func (r RegisterRequestCorporate) GetProjectId() uint64 {
	return r.CompanyId
}

func (r RegisterRequest) GetProjectId() uint64 {
	return 1
}

func (r RegisterRequestPrivate) GetCompanyName() string {
	return ""
}

func (r RegisterRequestCorporate) GetCompanyName() string {
	return ""
}

func (r RegisterRequest) GetCompanyName() string {
	return r.CompanyName
}

func (r RegisterRequestPrivate) GetUrl() string {
	return r.Url
}

func (r RegisterRequestCorporate) GetUrl() string {
	return r.Url
}

func (r RegisterRequest) GetUrl() string {
	return r.Url
}
