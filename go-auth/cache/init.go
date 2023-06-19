package cache

var Caching = Cache{}

type Cache struct {
	BlackList              BlackListCache
	LoginAttempt           LoginAttemptCache
	BlockedAccounts        BlockedAccountsCache
	TwoFactorAttempt       TwoFactorAttemptCache
	Totp                   TotpCache
	ConfirmationIpLinks    ConfirmationIpLinksCache
	ConfirmationEmailLinks ConfirmationEmailLinksCache
	ConfirmationNewDevice  ConfirmationNewDeviceCache
	ResetPassword          ResetPasswordCache
	Jwt                    JwtCache
	CorporateLogin         CorporateLoginCache
}

func (c *Cache) Init() *Cache {
	c.BlackList = BlackListCache{}
	c.LoginAttempt = LoginAttemptCache{}
	c.TwoFactorAttempt = TwoFactorAttemptCache{}
	c.BlockedAccounts = BlockedAccountsCache{}
	c.Totp = TotpCache{}
	c.ConfirmationIpLinks = ConfirmationIpLinksCache{}
	c.ConfirmationEmailLinks = ConfirmationEmailLinksCache{}
	c.ConfirmationNewDevice = ConfirmationNewDeviceCache{}
	c.ResetPassword = ResetPasswordCache{}
	c.Jwt = JwtCache{}
	c.CorporateLogin = CorporateLoginCache{}

	return c
}
