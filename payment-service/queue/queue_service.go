package queue

import (
	"github.com/go-redis/redis/v8"
	"github.com/spf13/viper"
	"log"
	"payment-service/providers"
)

// Service - сервис для управления очередями
type Service struct {
	Client *redis.Client
}

// NewService - создает новый экземпляр Service
func NewService(providersService *providers.Service) *Service {
	service := &Service{
		// Инициализация базы данных Redis
		Client: NewRedisClient(viper.GetString("redis.connection_string"), viper.GetString("redis.pass"), 0),
	}

	go func() {
		if err := StartConsumer(service, providersService, "your_queue_name"); err != nil {
			log.Fatalf("Error starting consumer: %v", err)
		}
	}()

	return service
}
