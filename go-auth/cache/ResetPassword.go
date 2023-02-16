package cache

import (
	"encoding/json"
	"sync"
)

type ResetPasswordCache struct {
	lock sync.Mutex
	Data map[string]ResetPasswordCacheData // Data should probably not have any reference fields
}

type ResetPasswordCacheData struct {
	Id                  uint64 `json:"id"`
	CompanyId           uint64 `json:"company_id"`
	FullName            string `json:"full_name"`
	Email               string `json:"email"`
	PasswordRecoveryUrl string `json:"password_recovery_url"`
	Type                string `json:"type"`
}

func (a *ResetPasswordCache) Get(key string) (*ResetPasswordCacheData, bool) {
	a.lock.Lock()
	defer a.lock.Unlock()
	d, ok := a.Data[key]
	return &d, ok
}

func (a *ResetPasswordCache) Set(key string, d *ResetPasswordCacheData) {
	a.lock.Lock()
	defer a.lock.Unlock()
	a.Data[key] = *d
}

func (a *ResetPasswordCache) Delete(key string) {
	a.lock.Lock()
	defer a.lock.Unlock()
	delete(a.Data, key)

	return
}

// MarshalBinary -
func (e *ResetPasswordCacheData) MarshalBinary() ([]byte, error) {
	return json.Marshal(e)
}

// UnmarshalBinary -
func (e *ResetPasswordCacheData) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &e); err != nil {
		return err
	}

	return nil
}
