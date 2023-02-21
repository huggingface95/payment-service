package cache

import (
	"encoding/json"
	"fmt"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/repositories/redisRepository"
	"time"
)

type ConfirmationIpLinksCache struct {
	CompanyId        uint64 `json:"company_id"`
	Id               uint64 `json:"id"`
	Provider         string `json:"provider"`
	Email            string `json:"email"`
	FullName         string `json:"full_name"`
	Ip               string `json:"ip"`
	CreatedAt        string `json:"created_at"`
	ConfirmationLink string `json:"confirmation_link"`
	ExpiredAt        *time.Time
}

// MarshalBinary -
func (c *ConfirmationIpLinksCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(c)
}

// UnmarshalBinary -
func (c *ConfirmationIpLinksCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &c); err != nil {
		return err
	}

	return nil
}

func (c *ConfirmationIpLinksCache) Get(id string, isFullPath bool) *ConfirmationIpLinksCache {
	if isFullPath == false {
		id = fmt.Sprintf(constants.CacheConfirmationIpLinks, id)
	}
	record := redisRepository.GetByKey(id, func() interface{} {
		return new(ConfirmationIpLinksCache)
	})
	if record == nil {
		return nil
	}

	return record.(*ConfirmationIpLinksCache)
}

func (c *ConfirmationIpLinksCache) Set(id string) {
	expiredAt := time.Now().Add(time.Second * 300)
	c.ExpiredAt = &expiredAt
	database.Set(fmt.Sprintf(constants.CacheConfirmationIpLinks, id), c)
}

func (c *ConfirmationIpLinksCache) Del(id string) {
	database.Del(fmt.Sprintf(constants.CacheConfirmationIpLinks, id))
}
