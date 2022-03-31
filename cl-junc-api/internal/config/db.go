package config

import "cl-junc-api/pkg/db/config"

type Db struct {
	Sql   config.SqlDbConfig `json:"sql"`
	Redis config.RedisConfig `json:"redis"`
}
