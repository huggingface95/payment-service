package database

import (
	"context"
	"fmt"
	"github.com/go-redis/redis/v8"
	"jwt-authentication-golang/config"
	"log"
	"time"
)

var RedisInstance *redis.Client

func RedisConnect(config *config.RedisConfig) {
	RedisInstance = redis.NewClient(&redis.Options{
		Addr:     fmt.Sprintf("%s:%d", config.Server, config.Port),
		Password: config.Password, // no password set
		DB:       config.Db,       // use default DB
	})
	if _, err := RedisInstance.Ping(context.Background()).Result(); err != nil {
		panic("Redis don't connect")
	}
	log.Println("Connected to Redis!")
}

func LRange(key string, start, end int64) []string {
	return RedisInstance.LRange(context.Background(), key, start, end).Val()
}

func BLPop(time time.Duration, key string) []string {
	return RedisInstance.BLPop(context.Background(), time, key).Val()
}

func AddList(key string, val ...interface{}) bool {
	cmd := RedisInstance.LPush(context.Background(), key, val)
	return cmd.Err() == nil
}

func RemRedisSet(key string, val interface{}) bool {
	cmd := RedisInstance.SRem(context.Background(), key, val)
	return cmd.Err() == nil
}

func Remove(key string, val interface{}) bool {
	cmd := RedisInstance.LRem(context.Background(), key, 1, val)
	return cmd.Err() == nil
}

func Set(key string, val interface{}) bool {
	cmd := RedisInstance.Set(context.Background(), key, val, -1)
	return cmd.Err() == nil
}

func Del(key string) bool {
	cmd := RedisInstance.Del(context.Background(), key)
	return cmd.Err() == nil
}

func Get(key string) string {
	return RedisInstance.Get(context.Background(), key).Val()
}

func GetKeys(pattern string) []string {
	return RedisInstance.Keys(context.Background(), pattern).Val()
}

func LIndex(key string) string {
	cmd := RedisInstance.LIndex(context.Background(), key, -1)
	return cmd.Val()
}
