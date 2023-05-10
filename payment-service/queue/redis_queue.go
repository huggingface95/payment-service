package queue

import "context"
import "github.com/go-redis/redis/v8"

var ctx = context.Background()

func NewRedisClient(addr, password string, db int) *redis.Client {
	return redis.NewClient(&redis.Options{
		Addr:     addr,
		Password: password,
		DB:       db,
	})
}
