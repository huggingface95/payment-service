package cache

import (
	"encoding/json"
	"sync"
)

type ConfirmationNewDeviceCache struct {
	lock sync.Mutex
	Data map[string]ConfirmationNewDeviceData
}

type ConfirmationNewDeviceData struct {
	CompanyId uint64 `json:"company_id"`
	FullName  string `json:"full_name"`
	Email     string `json:"email"`
	CreatedAt string `json:"created_at"`
	Ip        string `json:"ip"`
	Cookie    string `json:"cookie"`
	Os        string `json:"os"`
	Type      string `json:"type"`
	Model     string `json:"model"`
	Browser   string `json:"browser"`
}

func (a *ConfirmationNewDeviceCache) Get(key string) (*ConfirmationNewDeviceData, bool) {
	a.lock.Lock()
	defer a.lock.Unlock()
	d, ok := a.Data[key]
	return &d, ok
}

func (a *ConfirmationNewDeviceCache) Set(key string, d *ConfirmationNewDeviceData) {
	a.lock.Lock()
	defer a.lock.Unlock()
	a.Data[key] = *d
}

func (a *ConfirmationNewDeviceCache) Delete(key string) {
	a.lock.Lock()
	defer a.lock.Unlock()
	delete(a.Data, key)

	return
}

// MarshalBinary -
func (e *ConfirmationNewDeviceData) MarshalBinary() ([]byte, error) {
	return json.Marshal(e)
}

// UnmarshalBinary -
func (e *ConfirmationNewDeviceData) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &e); err != nil {
		return err
	}

	return nil
}
