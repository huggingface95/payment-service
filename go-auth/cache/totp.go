package cache

import (
	"encoding/json"
	"fmt"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/repositories/redisRepository"
	"time"
)

type TotpCache struct {
	Data      []byte
	ExpiredAt *time.Time
}

func (l *TotpCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(l)
}

func (l *TotpCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &l); err != nil {
		return err
	}

	return nil
}

func (l *TotpCache) GetOtpBytes(id string, isFullPath bool) []byte {

	totp := l.Get(id, isFullPath)
	if totp == nil {
		return nil
	}

	return totp.Data
}

func (l *TotpCache) Get(id string, isFullPath bool) *TotpCache {
	if isFullPath == false {
		id = fmt.Sprintf(constants.CacheTotp, id)
	}

	record := redisRepository.GetByKey(id, func() interface{} {
		return new(TotpCache)
	})
	if record == nil {
		return nil
	}
	return record.(*TotpCache)
}

func (l *TotpCache) Set(id string, data []byte) {
	expiredAt := time.Now().Add(time.Second * 300)
	l.ExpiredAt = &expiredAt
	l.Data = data
	database.Set(fmt.Sprintf(constants.CacheTotp, id), l)
}

func (l *TotpCache) Del(id string) {
	database.Del(fmt.Sprintf(constants.CacheTotp, id))
}
