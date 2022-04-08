package db

type EmailTemplate struct {
	Id      uint64 `bun:"id,pk,autoincrement"`
	Type    string `bun:"type,notnull"`
	Content string `bun:"content,notnull"`
}
