package cache

import (
	"encoding/json"
	"fmt"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/repositories/redisRepository"
	"time"
)

type ResetPasswordCache struct {
	Id                  uint64 `json:"id"`
	CompanyId           uint64 `json:"company_id"`
	FullName            string `json:"full_name"`
	Email               string `json:"email"`
	PasswordRecoveryUrl string `json:"password_recovery_url"`
	Type                string `json:"type"`
	ExpiredAt           *time.Time
}

// MarshalBinary -
func (c *ResetPasswordCache) MarshalBinary() ([]byte, error) {
	return json.Marshal(c)
}

// UnmarshalBinary -
func (c *ResetPasswordCache) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &c); err != nil {
		return err
	}

	return nil
}

func (c *ResetPasswordCache) Get(id string, isFullPath bool) *ResetPasswordCache {
	if isFullPath == false {
		id = fmt.Sprintf(constants.CacheResetPassword, id)
	}

	record := redisRepository.GetByKey(id, func() interface{} {
		return new(ResetPasswordCache)
	})
	if record == nil {
		return nil
	}

	return record.(*ResetPasswordCache)
}

func (c *ResetPasswordCache) Set(id string) {
	expiredAt := time.Now().Add(time.Second * 300)
	c.ExpiredAt = &expiredAt
	database.Set(fmt.Sprintf(constants.CacheResetPassword, id), c)
}

func (c *ResetPasswordCache) Del(id string) {
	database.Del(fmt.Sprintf(constants.CacheResetPassword, id))
}
