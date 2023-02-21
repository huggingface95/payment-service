package cache

import (
	"encoding/json"
	"fmt"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/repositories/redisRepository"
	"time"
)

type BlockedAccountsCache struct {
	ExpiredAt *time.Time
}

func (b *BlockedAccountsCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(b)
}

func (b *BlockedAccountsCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &b); err != nil {
		return err
	}
	return nil
}

func (b *BlockedAccountsCache) Has(id string) bool {
	record := redisRepository.GetByKey(fmt.Sprintf(constants.CacheBlockedAccounts, id), func() interface{} {
		return new(BlockedAccountsCache)
	})
	if record == nil {
		return false
	}
	return true
}

func (b *BlockedAccountsCache) GetExpiredAt(id string) *time.Time {
	record := redisRepository.GetByKey(fmt.Sprintf(constants.CacheBlockedAccounts, id), func() interface{} {
		return new(BlockedAccountsCache)
	})
	if record == nil {
		return nil
	}
	blocked := record.(*BlockedAccountsCache)
	return blocked.ExpiredAt
}

func (b *BlockedAccountsCache) Set(id string, value *time.Time) {
	b.ExpiredAt = value
	database.Set(fmt.Sprintf(constants.CacheBlockedAccounts, id), b)
}

func (b *BlockedAccountsCache) Del(id string) {
	database.Del(fmt.Sprintf(constants.CacheBlockedAccounts, id))
}
