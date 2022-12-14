package config

import (
	"os"
	"strconv"
)

var RedisConf = RedisConfig{}

type RedisConfig struct {
	Server   string
	Port     int
	Db       int
	Password string
}

func (r *RedisConfig) Load() *RedisConfig {
	dbId, err := strconv.Atoi(os.Getenv("REDIS_DB_ID"))
	if err != nil {
		panic(err)
	}
	port, err := strconv.Atoi(os.Getenv("REDIS_PORT"))
	if err != nil {
		panic(err)
	}
	r.Db = dbId
	r.Port = port
	r.Server = os.Getenv("REDIS_SERVER")
	r.Password = os.Getenv("REDIS_PASSWORD")

	return r
}
