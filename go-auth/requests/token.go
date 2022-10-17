package requests

type LoginRequest struct {
	Email    string `form:"email" binding:"required"`
	Password string `form:"password" binding:"required"`
	Type     string `form:"type" binding:"required"`
	Proceed  bool   `form:"proceed"`
	Cancel   bool   `form:"cancel"`
}

type HeaderRequest struct {
	HttpClientIp      string `header:"HTTP_CLIENT_IP"`
	HttpXForwardedFor string `header:"HTTP_X_FORWARDED_FOR"`
}
