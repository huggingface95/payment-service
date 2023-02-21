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
}

func (c *Cache) Init() *Cache {
	c.BlackList = BlackListCache{}
	c.LoginAttempt = LoginAttemptCache{}
	c.TwoFactorAttempt = TwoFactorAttemptCache{Data: make(map[string]int)}
	c.BlockedAccounts = BlockedAccountsCache{}
	c.Totp = TotpCache{}
	c.ConfirmationIpLinks = ConfirmationIpLinksCache{}
	c.ConfirmationEmailLinks = ConfirmationEmailLinksCache{}
	c.ConfirmationNewDevice = ConfirmationNewDeviceCache{}
	c.ResetPassword = ResetPasswordCache{}

	return c
}
