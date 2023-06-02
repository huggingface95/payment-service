package clearjunction

import (
	"payment-service/db"
	"payment-service/providers"
	"payment-service/queue"
)

type Services struct {
	Providers *providers.Service
	Queue     *queue.Service
	DB        *db.Service
}
