package individual

import "jwt-authentication-golang/constants"

const RegisterStandard = "RegisterRequest"
const RegisterPrivate = "RegisterRequestPrivate"
const RegisterCorporate = "RegisterRequestCorporate"

type RegisterApplicantInterface interface {
	GetType() string
	GetApplicantModel() string
	GetPassword() string
	GetEmail() string
	GetCompanyId() uint64
	GetCountryId() *uint64
	GetProjectId() uint64
	GetCompanyName() string
	GetUrl() string
	SetCompanyId(cId uint64)
	SetProjectId(pId uint64)
}

type RegisterInternalApplicant struct {
	Sign string                 `json:"sign" binding:"required"`
	Data map[string]interface{} `json:"data" binding:"required"`
}

type RegisterRequestApplicant struct {
	CompanyId uint64 `json:"company_id,omitempty"`
	ProjectId uint64 `json:"project_id,omitempty"`
	Url       string `json:"url"`
	Email     string `json:"email" binding:"required,email"`
	Phone     string `json:"phone" binding:"required,e164"`
	CountryId uint64 `json:"country,omitempty"`
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
	FirstName string `json:"firstName" binding:"required"`
	LastName  string `json:"lastName" binding:"required"`
	Password  string `json:"password" binding:"required"`
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

func (r RegisterRequest) GetApplicantModel() string {
	if r.ClientType == constants.RegisterClientTypeCorporate {
		return constants.ModelCorporate
	}
	return constants.ModelIndividual
}

func (r RegisterRequestPrivate) GetApplicantModel() string {
	return constants.ModelIndividual
}

func (r RegisterRequestCorporate) GetApplicantModel() string {
	return constants.ModelCorporate
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

func (r RegisterRequestPrivate) GetEmail() string {
	return r.Email
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

func (r RegisterRequestCorporate) SetCompanyId(cId uint64) {
	r.CompanyId = cId
}

func (r RegisterRequestPrivate) SetCompanyId(cId uint64) {
	r.CompanyId = cId
}

func (r RegisterRequest) SetCompanyId(cId uint64) {

}

func (r RegisterRequestCorporate) SetProjectId(pId uint64) {
	r.ProjectId = pId
}

func (r RegisterRequestPrivate) SetProjectId(pId uint64) {
	r.ProjectId = pId
}

func (r RegisterRequest) SetProjectId(pId uint64) {

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
