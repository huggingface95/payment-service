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

func (e *LoginAttemptCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(e)
}

func (e *LoginAttemptCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &e); err != nil {
		return err
	}

	return nil
}

func (l *LoginAttemptCache) GetAttempt(id string) int {
	record := redisRepository.GetByKey(fmt.Sprintf(constants.CacheLoginAttempt, id), func() interface{} {
		return new(LoginAttemptCache)
	})
	if record == nil {
		return 0
	}
	loginAttempt := record.(*LoginAttemptCache)
	return loginAttempt.Count
}

func (l *LoginAttemptCache) GetExpiredAt(id string) *time.Time {
	record := redisRepository.GetByKey(fmt.Sprintf(constants.CacheLoginAttempt, id), func() interface{} {
		return new(LoginAttemptCache)
	})
	if record == nil {
		return nil
	}
	loginAttempt := record.(*LoginAttemptCache)
	return loginAttempt.ExpiredAt
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
