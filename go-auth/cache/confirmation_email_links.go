package cache

import (
	"encoding/json"
	"sync"
)

type ConfirmationEmailLinksCache struct {
	lock sync.Mutex
	Data map[string]ConfirmationEmailLinksData // Data should probably not have any reference fields
}

type ConfirmationEmailLinksData struct {
	Id               uint64 `json:"id"`
	FullName         string `json:"full_name"`
	Email            string `json:"email"`
	ConfirmationLink string `json:"confirmation_link"`
}

func (a *ConfirmationEmailLinksCache) Get(key string) (*ConfirmationEmailLinksData, bool) {
	a.lock.Lock()
	defer a.lock.Unlock()
	d, ok := a.Data[key]
	return &d, ok
}

func (a *ConfirmationEmailLinksCache) Set(key string, d *ConfirmationEmailLinksData) {
	a.lock.Lock()
	defer a.lock.Unlock()
	a.Data[key] = *d
}

func (a *ConfirmationEmailLinksCache) Delete(key string) {
	a.lock.Lock()
	defer a.lock.Unlock()
	delete(a.Data, key)

	return
}

// MarshalBinary -
func (e *ConfirmationEmailLinksData) MarshalBinary() ([]byte, error) {
	return json.Marshal(e)
}

// UnmarshalBinary -
func (e *ConfirmationEmailLinksData) UnmarshalBinary(data []byte) error {
	if err := json.Unmarshal(data, &e); err != nil {
		return err
	}

	return nil
}
