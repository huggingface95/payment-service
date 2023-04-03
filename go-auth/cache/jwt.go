package cache

import (
	"encoding/json"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/repositories/redisRepository"
	"time"
)

type JwtCache struct {
	ExpiredAt time.Time
	Token     string
}

func (j *JwtCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(j)
}

func (j *JwtCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &j); err != nil {
		return err
	}
	return nil
}

func (j *JwtCache) Get(id string) *JwtCache {
	record := redisRepository.GetByKey(id, func() interface{} {
		return new(JwtCache)
	})
	if record == nil {
		return nil
	}

	return record.(*JwtCache)
}

func (j *JwtCache) Set(id string) {
	database.Set(id, j)
}

//func (j *JwtCache) Del(id string) {
//	database.Del(fmt.Sprintf(constants.CacheJwt, id))
//}
