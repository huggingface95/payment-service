package config

import (
	"os"
	"strconv"
)

var PostgresConf = PostgresConfig{}

type PostgresConfig struct {
	Host     string
	Login    string
	Password string
	Database string
	Port     int
	SslMode  string
	TimeZone string
}

func (p *PostgresConfig) Load() *PostgresConfig {
	port, err := strconv.Atoi(os.Getenv("POSTGRES_PORT"))
	if err != nil {
		panic(err)
	}
	p.Port = port
	p.Host = os.Getenv("POSTGRES_HOST")
	p.Login = os.Getenv("POSTGRES_LOGIN")
	p.Password = os.Getenv("POSTGRES_PASSWORD")
	p.Database = os.Getenv("POSTGRES_DATABASE")
	p.SslMode = os.Getenv("POSTGRES_SSL_MODE")
	p.TimeZone = os.Getenv("POSTGRES_TIME_ZONE")

	return p
}
