package cache

import (
	"encoding/json"
	"fmt"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/repositories/redisRepository"
)

type BlackListCache struct {
	Data []*BlackListData
}

type BlackListData struct {
	Forever bool
	Token   string
}

func (bList *BlackListCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(bList)
}

func (bList *BlackListCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &bList); err != nil {
		return err
	}

	return nil
}

func (bList *BlackListCache) HasToken(id string, token string) bool {
	record := redisRepository.GetByKey(fmt.Sprintf(constants.CacheAuthBlackList, id), func() interface{} {
		return new(BlackListCache)
	})
	if record == nil {
		return false
	}

	blackList := record.(*BlackListCache)

	for _, val := range blackList.Data {
		if val.Token == token {
			return true
		}
	}

	return false
}

func (bList *BlackListCache) Get(id string) *BlackListCache {
	record := redisRepository.GetByKey(fmt.Sprintf(constants.CacheAuthBlackList, id), func() interface{} {
		return new(BlackListCache)
	})
	if record == nil {
		return nil
	}
	return record.(*BlackListCache)
}

func (bList *BlackListCache) Set(id string, data *BlackListData) {
	var blackList *BlackListCache

	record := redisRepository.GetByKey(fmt.Sprintf(constants.CacheAuthBlackList, id), func() interface{} {
		return new(BlackListCache)
	})
	if record != nil {
		blackList = record.(*BlackListCache)
	} else {
		blackList = bList
	}
	blackList.Data = append(blackList.Data, data)

	database.Set(fmt.Sprintf(constants.CacheAuthBlackList, id), blackList)
}

func (bList *BlackListCache) Del(id string) {
	database.Del(fmt.Sprintf(constants.CacheAuthBlackList, id))
}
