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
	Name   string
}

// NewService - создает новый экземпляр Service
func NewService(providersService *providers.Service) *Service {
	service := &Service{
		// Инициализация базы данных Redis
		Client: NewRedisClient(viper.GetString("redis.connection_string"), viper.GetString("redis.pass"), 0),
		// Получение имени очереди из конфига
		Name: viper.GetString("redis.queue_name"),
	}

	go func() {
		if err := StartConsumer(service, providersService); err != nil {
			log.Fatalf("Error starting consumer: %v", err)
		}
	}()

	return service
}
