package providers

const (
	PostbackTypeIBAN   PostbackTypeEnum = "ibanAllocationNotification"
	PostbackTypePayIn  PostbackTypeEnum = "payinNotification"
	PostbackTypePayOut PostbackTypeEnum = "payoutNotification"
)

type PostbackTypeEnum string
