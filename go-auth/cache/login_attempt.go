package cache

import (
	"encoding/json"
	"fmt"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/repositories/redisRepository"
	"jwt-authentication-golang/times"
	"time"
)

type LoginAttemptCache struct {
	Count     int
	ExpiredAt *time.Time
}

func (l *LoginAttemptCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(l)
}

func (l *LoginAttemptCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &l); err != nil {
		return err
	}

	return nil
}

func (l *LoginAttemptCache) GetAttempt(id string, isFullPath bool) int {
	data := l.Get(id, isFullPath)
	if data != nil {
		return data.Count
	}

	return 0
}

func (l *LoginAttemptCache) Get(id string, isFullPath bool) *LoginAttemptCache {
	if isFullPath == false {
		id = fmt.Sprintf(constants.CacheLoginAttempt, id)
	}

	record := redisRepository.GetByKey(id, func() interface{} {
		return new(LoginAttemptCache)
	})
	if record == nil {
		return nil
	}
	return record.(*LoginAttemptCache)
}

func (l *LoginAttemptCache) Set(id string, value int) {
	_, bTime, _, _, _ := times.GetTokenTimes()
	l.Count = value
	l.ExpiredAt = &bTime

	database.Set(fmt.Sprintf(constants.CacheLoginAttempt, id), l)
}

func (l *LoginAttemptCache) Del(id string) {
	database.Del(fmt.Sprintf(constants.CacheLoginAttempt, id))
}
