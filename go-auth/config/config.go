package config

import (
	"github.com/joho/godotenv"
	"log"
)

var Conf = Config{}

type Config struct {
	App        *AppConfig
	Redis      *RedisConfig
	Postgres   *PostgresConfig
	ClickHouse *ClickhouseConfig
	Jwt        *JwtConfig
	Email      *EmailConfig
}

func (c *Config) Init() *Config {
	// load .env file
	err := godotenv.Load(".env")

	if err != nil {
		log.Fatalf("Error loading .env file")
	}
	c.App = AppConf.Load()
	c.Redis = RedisConf.Load()
	c.Postgres = PostgresConf.Load()
	c.ClickHouse = ClickhouseConf.Load()
	c.Jwt = JwtConf.Load()
	c.Email = EMailConf.Load()

	return c
}
