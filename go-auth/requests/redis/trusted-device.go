package redis

type TrustedDeviceRequest struct {
	CompanyId string `json:"companyId"`
	FullName  string `json:"fullName"`
	Email     string `json:"email"`
	CreatedAt string `json:"createdAt"`
	Ip        string `json:"ip"`
	Cookie    string `json:"cookie"`
	Os        string `json:"os"`
	Type      string `json:"type"`
	Model     string `json:"model"`
	Browser   string `json:"browser"`
}
