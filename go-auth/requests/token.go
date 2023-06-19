package requests

type LoginRequest struct {
	Email    string `json:"email" binding:"required"`
	Password string `json:"password" binding:"required"`
	Type     string `json:"client_type"`
	Proceed  bool   `json:"proceed"`
	Cancel   bool   `json:"cancel"`
}

type SelectedAccountRequest struct {
	Token string `json:"token" binding:"required"`
	Type  string `json:"type" binding:"required,oneof=corporate individual"`
}

type RefreshRequest struct {
	Type string `json:"client_type"`
}

type HeaderRequest struct {
	HttpClientIp      string `header:"HTTP_CLIENT_IP"`
	HttpXForwardedFor string `header:"HTTP_X_FORWARDED_FOR"`
}
