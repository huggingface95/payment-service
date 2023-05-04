package main

import (
	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/logger"
	"github.com/pkg/errors"
	"github.com/spf13/viper"
	"payment-service/api"
	"payment-service/db"
	"payment-service/queue"
	"strings"
)

func main() {
	app := fiber.New()
	app.Use(logger.New())

	// Загрузка конфигурации
	if err := loadConfig(); err != nil {
		panic(err)
	}

	// Инициализация базы данных
	dbConn, err := db.NewDB(viper.GetString("db.connection_string"))
	if err != nil {
		panic(err)
	}
	defer dbConn.Close()

	// Подключение к RabbitMQ
	rabbitConn, err := queue.ConnectRabbitMQ(viper.GetString("rabbitmq.connection_string"))
	if err != nil {
		panic(err)
	}
	defer rabbitConn.Close()

	// Инициализация обработчика очередей
	go func() {
		err := queue.StartConsumer(rabbitConn, "payment_tasks")
		if err != nil {
			panic(err)
		}
	}()

	// Настройка маршрутизации
	api.SetupRoutes(app)

	// Запуск приложения
	if err := app.Listen(viper.GetString("server.address")); err != nil {
		panic(err)
	}
}

func loadConfig() error {
	viper.SetConfigName("config")
	viper.AddConfigPath(".")

	if err := viper.ReadInConfig(); err != nil {
		return errors.Wrap(err, "cannot read the config.yaml")
	}

	viper.SetConfigName("config.local")
	if err := viper.MergeInConfig(); err != nil {
		return err
	}

	// Поддержка переменных окружения
	viper.AutomaticEnv()
	viper.SetEnvKeyReplacer(strings.NewReplacer(".", "_"))

	return nil
}
