package cache

var Caching = Cache{}

type Cache struct {
	AuthUser               AuthUserCache
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
	c.AuthUser = AuthUserCache{Data: make(map[string]AuthUserData)}
	c.BlackList = BlackListCache{}
	c.LoginAttempt = LoginAttemptCache{Data: make(map[string]int)}
	c.TwoFactorAttempt = TwoFactorAttemptCache{Data: make(map[string]int)}
	c.BlockedAccounts = BlockedAccountsCache{Data: make(map[string]int64)}
	c.Totp = TotpCache{Data: make(map[string][]byte)}
	c.ConfirmationIpLinks = ConfirmationIpLinksCache{Data: make(map[string]ConfirmationIpLinksData)}
	c.ConfirmationEmailLinks = ConfirmationEmailLinksCache{Data: make(map[string]ConfirmationEmailLinksData)}
	c.ConfirmationNewDevice = ConfirmationNewDeviceCache{Data: make(map[string]ConfirmationNewDeviceData)}
	c.ResetPassword = ResetPasswordCache{Data: make(map[string]ResetPasswordCacheData)}

	return c
}
