package db

import (
	"cl-junc-api/pkg/db/config"
	"context"
	"fmt"
	"github.com/go-redis/redis/v8"
	"time"
)

type RedisDb struct {
	client *redis.Client
}

func NewRedisDb(config config.RedisConfig) RedisDb {
	client := redis.NewClient(&redis.Options{
		Addr:     fmt.Sprintf("%s:%d", config.Server, config.Port),
		Password: config.Password, // no password set
		DB:       config.DbId,     // use default DB
	})

	return RedisDb{client: client}
}

func (r *RedisDb) Set(key string, val interface{}) error {
	return r.client.Set(context.Background(), key, val, 0).Err()
}

func (r *RedisDb) SetEx(key string, val interface{}, expiration time.Duration) error {
	return r.client.Set(context.Background(), key, val, expiration).Err()
}

func (r *RedisDb) Get(key string) string {
	return r.client.Get(context.Background(), key).Val()
}

func (r *RedisDb) GetCmd(key string) *redis.StringCmd {
	return r.client.Get(context.Background(), key)
}

func (r *RedisDb) SetLock(key string) {
	r.SetLockEx(key, time.Hour)
}

func (r *RedisDb) SetLockEx(key string, duration time.Duration) {
	r.client.Set(context.Background(), "_tmp:lock:"+key, "1", duration)
}

func (r *RedisDb) GetLock(key string) bool {
	return r.client.Get(context.Background(), "_tmp:lock:"+key).Val() == "1"
}

func (r *RedisDb) GetSetLockEx(key string, duration time.Duration) bool {
	if r.GetLock(key) {
		return true
	}

	r.SetLockEx(key, duration)
	return false
}

func (r *RedisDb) SetRedisHash(key string, val interface{}) {
	r.client.HSet(context.Background(), key, val)
}

func (r *RedisDb) GetRedisHash(key string) map[string]string {
	cmd := r.client.HGetAll(context.Background(), key)
	return cmd.Val()
}

func (r *RedisDb) SetRedisHashField(key string, field, val interface{}) {
	r.client.HSet(context.Background(), key, field, val)
}

func (r *RedisDb) GetRedisHashField(key string, field string) string {
	cmd := r.client.HGet(context.Background(), key, field)
	return cmd.Val()
}

func (r *RedisDb) GetRedisSet(key string) []string {
	cmd := r.client.SMembers(context.Background(), key)
	return cmd.Val()
}

func (r *RedisDb) HasRedisSet(key, val string) bool {
	cmd := r.client.SIsMember(context.Background(), key, val)
	return cmd.Val()
}

func (r *RedisDb) SetRedisSet(key string, set []string) bool {
	cmd := r.client.Del(context.Background(), key)
	if cmd.Err() != nil {
		return false
	}
	cmd = r.client.SAdd(context.Background(), key, set)
	return cmd.Err() == nil
}

func (r *RedisDb) AddRedisSet(key string, val interface{}) bool {
	cmd := r.client.SAdd(context.Background(), key, val)
	return cmd.Err() == nil
}

func (r *RedisDb) AddList(key string, val ...interface{}) bool {
	cmd := r.client.LPush(context.Background(), key, val)
	//fmt.Println(cmd.Err())
	//fmt.Println("errrorrrrrrr")
	return cmd.Err() == nil
}

func (r *RedisDb) RemRedisSet(key string, val interface{}) bool {
	cmd := r.client.SRem(context.Background(), key, val)
	return cmd.Err() == nil
}
