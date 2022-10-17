package redisRepository

import (
	"encoding/json"
	"jwt-authentication-golang/database"
	"time"
)

func GetRedisDataByBlPop(key string, mc func() interface{}) interface{} {
	row := database.BLPop(time.Second, key)

	model := mc()

	if len(row) < 2 {
		return nil
	}
	err := json.Unmarshal([]byte(row[1]), model)
	if err != nil {
		return nil
	}

	return model
}

func SetRedisDataByBlPop(key string, val interface{}) bool {
	return database.AddList(key, val)
}
