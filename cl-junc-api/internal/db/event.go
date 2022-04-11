package db

import "time"

type Event struct {
	Title       string      `bun:"Title,notnull"`
	Data        interface{} `bun:"Data,type:jsonb"`
	DateCreated time.Time   `bun:"DateCreated,nullzero,notnull,default:current_timestamp"`
}
