package main

import (
	"github.com/pkg/errors"
	"github.com/spf13/viper"
	"payment-service/app"
	"strings"
)

func main() {
	// Загрузка конфигурации
	if err := loadConfig(); err != nil {
		panic(err)
	}

	// Запуск приложения
	app.Start()
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
