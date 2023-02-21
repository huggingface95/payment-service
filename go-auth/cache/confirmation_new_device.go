package cache

import (
	"encoding/json"
	"fmt"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/repositories/redisRepository"
	"time"
)

type ConfirmationNewDeviceCache struct {
	CompanyId uint64 `json:"company_id"`
	FullName  string `json:"full_name"`
	Email     string `json:"email"`
	CreatedAt string `json:"created_at"`
	Ip        string `json:"ip"`
	Os        string `json:"os"`
	Type      string `json:"type"`
	Model     string `json:"model"`
	Browser   string `json:"browser"`
	ExpiredAt *time.Time
}

// MarshalBinary -
func (c *ConfirmationNewDeviceCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(c)
}

// UnmarshalBinary -
func (c *ConfirmationNewDeviceCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &c); err != nil {
		return err
	}

	return nil
}

func (c *ConfirmationNewDeviceCache) Get(id string, isFullPath bool) *ConfirmationNewDeviceCache {
	if isFullPath == false {
		id = fmt.Sprintf(constants.CacheConfirmationNewDevice, id)
	}

	record := redisRepository.GetByKey(id, func() interface{} {
		return new(ConfirmationNewDeviceCache)
	})
	if record == nil {
		return nil
	}

	return record.(*ConfirmationNewDeviceCache)
}

func (c *ConfirmationNewDeviceCache) Set(id string) {
	expiredAt := time.Now().Add(time.Second * 300)
	c.ExpiredAt = &expiredAt
	database.Set(fmt.Sprintf(constants.CacheConfirmationNewDevice, id), c)
}

func (c *ConfirmationNewDeviceCache) Del(id string) {
	database.Del(fmt.Sprintf(constants.CacheConfirmationNewDevice, id))
}
