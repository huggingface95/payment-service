package api

import (
	"payment-service/db"
	"payment-service/providers"
	"payment-service/queue"
)

type Services struct {
	API       *Service
	Providers *providers.Service
	Queue     *queue.Service
	DB        *db.Service
}
