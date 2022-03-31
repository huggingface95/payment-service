package models

import "time"

type PostbackNotificationType string

const (
	PayoutNotification       PostbackNotificationType = "payoutNotification"
	PayoutReturnNotification                          = "payoutReturnNotification"

	PayinNotification = "payinNotification"
)

type Postback struct {
	ClientOrder    string                   `json:"clientOrder"`
	OrderReference string                   `json:"orderReference"`
	Type           PostbackNotificationType `json:"type"`
	MessageUUID    string                   `json:"messageUuid"`
	Messages       []Message                `json:"messages"`
	OperTimestamp  time.Time                `json:"operTimestamp"`
}

func (p *Postback) GetPostbackTime() time.Time {
	return p.OperTimestamp
}

type PostbackCommon interface {
	GetPostbackTime() time.Time
}
