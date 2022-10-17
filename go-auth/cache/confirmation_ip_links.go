package cache

import (
	"encoding/json"
	"sync"
)

type ConfirmationIpLinksCache struct {
	lock sync.Mutex
	Data map[string]ConfirmationIpLinksData // Data should probably not have any reference fields
}

type ConfirmationIpLinksData struct {
	CompanyId        uint64 `json:"company_id"`
	Id               uint64 `json:"id"`
	Provider         string `json:"provider"`
	Email            string `json:"email"`
	FullName         string `json:"full_name"`
	Ip               string `json:"ip"`
	CreatedAt        string `json:"created_at"`
	ConfirmationLink string `json:"confirmation_link"`
}

func (a *ConfirmationIpLinksCache) Get(key string) (*ConfirmationIpLinksData, bool) {
	a.lock.Lock()
	defer a.lock.Unlock()
	d, ok := a.Data[key]
	return &d, ok
}

func (a *ConfirmationIpLinksCache) Set(key string, d *ConfirmationIpLinksData) {
	a.lock.Lock()
	defer a.lock.Unlock()
	a.Data[key] = *d
}

func (a *ConfirmationIpLinksCache) Delete(key string) {
	a.lock.Lock()
	defer a.lock.Unlock()
	delete(a.Data, key)

	return
}

// MarshalBinary -
func (e *ConfirmationIpLinksData) MarshalBinary() ([]byte, error) {
	return json.Marshal(e)
}

// UnmarshalBinary -
func (e *ConfirmationIpLinksData) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &e); err != nil {
		return err
	}

	return nil
}
