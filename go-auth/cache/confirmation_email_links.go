package cache

import (
	"encoding/json"
	"fmt"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/repositories/redisRepository"
	"time"
)

type ConfirmationEmailLinksCache struct {
	Id               uint64 `json:"id"`
	FullName         string `json:"full_name"`
	Email            string `json:"email"`
	ConfirmationLink string `json:"confirmation_link"`
	CompanyId        uint64 `json:"company_id"`
	Type             string `json:"type"`
	ExpiredAt        *time.Time
}

// MarshalBinary -
func (c *ConfirmationEmailLinksCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(c)
}

// UnmarshalBinary -
func (c *ConfirmationEmailLinksCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &c); err != nil {
		return err
	}

	return nil
}

func (c *ConfirmationEmailLinksCache) Get(id string) *ConfirmationEmailLinksCache {
	record := redisRepository.GetByKey(fmt.Sprintf(constants.CacheConfirmationEmailLinks, id), func() interface{} {
		return new(ConfirmationEmailLinksCache)
	})
	if record == nil {
		return nil
	}

	return record.(*ConfirmationEmailLinksCache)
}

func (c *ConfirmationEmailLinksCache) Set(id string) {
	expiredAt := time.Now().Add(time.Second * 300)
	c.ExpiredAt = &expiredAt
	database.Set(fmt.Sprintf(constants.CacheConfirmationEmailLinks, id), c)
}

func (c *ConfirmationEmailLinksCache) Del(id string) {
	database.Del(fmt.Sprintf(constants.CacheConfirmationEmailLinks, id))
}
