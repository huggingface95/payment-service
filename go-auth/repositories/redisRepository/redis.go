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

func GetRedisListByKey(key string, mc func() interface{}) (list []interface{}) {
	for _, key := range database.GetKeys(key) {
		redisData := GetByKey(key, mc)
		list = append(list, redisData)
		database.Del(key)
	}
	return
}

func SetRedisDataByBlPop(key string, val interface{}) bool {
	return database.AddList(key, val)
}

func GetByKey(key string, callback func() interface{}) interface{} {
	row := database.Get(key)
	if row == "" {
		return nil
	}
	model := callback()
	err := json.Unmarshal([]byte(row), &model)
	if err != nil {
		return nil
	}

	return model
}
