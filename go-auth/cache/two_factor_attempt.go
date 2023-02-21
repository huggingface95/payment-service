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

type TwoFactorAttemptCache struct {
	Count     int
	ExpiredAt *time.Time
}

func (l *TwoFactorAttemptCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(l)
}

func (l *TwoFactorAttemptCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &l); err != nil {
		return err
	}

	return nil
}

func (l *TwoFactorAttemptCache) GetAttempt(id string, isFullPath bool) int {
	data := l.Get(id, isFullPath)
	if data != nil {
		return data.Count
	}

	return 0
}

func (l *TwoFactorAttemptCache) Get(id string, isFullPath bool) *TwoFactorAttemptCache {
	if isFullPath == false {
		id = fmt.Sprintf(constants.CacheTwoFactorLoginAttempt, id)
	}

	record := redisRepository.GetByKey(id, func() interface{} {
		return new(TwoFactorAttemptCache)
	})
	if record == nil {
		return nil
	}
	return record.(*TwoFactorAttemptCache)
}

func (l *TwoFactorAttemptCache) Set(id string, value int) {
	_, bTime, _, _, _ := times.GetTokenTimes()
	l.Count = value
	l.ExpiredAt = &bTime

	database.Set(fmt.Sprintf(constants.CacheTwoFactorLoginAttempt, id), l)
}

func (l *TwoFactorAttemptCache) Del(id string) {
	database.Del(fmt.Sprintf(constants.CacheTwoFactorLoginAttempt, id))
}
