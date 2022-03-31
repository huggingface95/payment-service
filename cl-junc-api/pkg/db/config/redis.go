package config

type RedisConfig struct {
	Server   string `json:"server"`
	Port     int    `json:"port"`
	Password string `json:"password"`
	DbId     int    `json:"db_id"`
}
