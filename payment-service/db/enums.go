package db

const (
	AccountStateIDNone = iota
	AccountStateIDWaitingForApproval
	AccountStateIDWaitingForAccountIbanGeneration
	AccountStateIDAwaitingAccount
	AccountStateIDActive
	AccountStateIDClosed
	AccountStateIDSuspended
	AccountStateIDRejected
)
