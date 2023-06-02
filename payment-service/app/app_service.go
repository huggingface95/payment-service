package app

import (
	"payment-service/api"
	"payment-service/db"
	"payment-service/providers"
	"payment-service/queue"
)

// Service - сервис для управления приложением
type Service struct {
	ProvidersService *providers.Service
	QueueService     *queue.Service
	DBService        *db.Service
	APIService       *api.Service
}

// NewService - создает новый экземпляр Service
func NewService() *Service {
	return &Service{}
}

func Start() {
	service := NewService()

	service.ProvidersService = providers.NewService()
	service.QueueService = queue.NewService(service.ProvidersService)
	service.DBService = db.NewService()
	service.APIService = api.NewService(api.Services{
		Providers: service.ProvidersService,
		Queue:     service.QueueService,
		DB:        service.DBService,
	})

	defer service.DBService.Pg.Close()

	// Запуск HTTP сервера приложения
	if err := service.APIService.FiberClient.Listen(service.APIService.Config["address"].(string)); err != nil {
		panic(err)
	}

	return
}
