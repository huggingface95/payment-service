package cache

import (
	"encoding/json"
	"fmt"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/helpers"
	"jwt-authentication-golang/repositories/redisRepository"
	"time"
)

type CorporateLoginCache struct {
	Data      map[string]uint64
	ExpiredAt time.Time
}

func (l *CorporateLoginCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(l)
}

func (l *CorporateLoginCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &l); err != nil {
		return err
	}

	return nil
}

func (l *CorporateLoginCache) Get(token string) *CorporateLoginCache {
	record := redisRepository.GetByKey(fmt.Sprintf(constants.CacheCorporateToken, token), func() interface{} {
		return new(CorporateLoginCache)
	})
	if record == nil {
		return nil
	}
	return record.(*CorporateLoginCache)
}

func (l *CorporateLoginCache) Set(individualId uint64, corporateId uint64) string {
	l.ExpiredAt = time.Now().Add(time.Minute * 10)
	l.Data = map[string]uint64{
		constants.Individual: individualId,
		constants.Corporate:  corporateId,
	}
	token := helpers.GenerateRandomString(10)
	database.Set(fmt.Sprintf(constants.CacheCorporateToken, token), l)
	return token
}

func (l *CorporateLoginCache) Del(token string) {
	database.Del(fmt.Sprintf(constants.CacheCorporateToken, token))
}
