package config

import (
	"os"
	"strconv"
)

var ClickhouseConf = ClickhouseConfig{}

type ClickhouseConfig struct {
	Host         string
	Login        string
	Password     string
	Database     string
	Port         int
	TimeoutRead  string
	TimeoutWrite string
}

func (c *ClickhouseConfig) Load() *ClickhouseConfig {
	port, err := strconv.Atoi(os.Getenv("CLICKHOUSE_PORT"))
	if err != nil {
		panic(err)
	}
	c.Port = port
	c.Host = os.Getenv("CLICKHOUSE_HOST")
	c.Login = os.Getenv("CLICKHOUSE_LOGIN")
	c.Password = os.Getenv("CLICKHOUSE_PASSWORD")
	c.Database = os.Getenv("CLICKHOUSE_DATABASE")
	c.TimeoutRead = os.Getenv("CLICKHOUSE_READ_TIMEOUT")
	c.TimeoutWrite = os.Getenv("CLICKHOUSE_WRITE_TIMEOUT")

	return c
}
