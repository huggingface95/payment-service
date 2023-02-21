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

func (b *BlockedAccountsCache) Has(id string, isFullPath bool) bool {
	record := b.Get(id, isFullPath)
	if record == nil {
		return false
	}
	return true
}

func (b *BlockedAccountsCache) Get(id string, isFullPath bool) *BlockedAccountsCache {
	if isFullPath == false {
		id = fmt.Sprintf(constants.CacheBlockedAccounts, id)
	}
	record := redisRepository.GetByKey(id, func() interface{} {
		return new(BlockedAccountsCache)
	})
	if record == nil {
		return nil
	}
	return record.(*BlockedAccountsCache)
}

func (b *BlockedAccountsCache) Set(id string, value *time.Time) {
	b.ExpiredAt = value
	database.Set(fmt.Sprintf(constants.CacheBlockedAccounts, id), b)
}

func (b *BlockedAccountsCache) Del(id string) {
	database.Del(fmt.Sprintf(constants.CacheBlockedAccounts, id))
}
