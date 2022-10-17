package cache

import (
	"sync"
	"time"
)

type AuthUserCache struct {
	lock sync.Mutex
	Data map[string]AuthUserData // Data should probably not have any reference fields
}

type AuthUserData struct {
	ExpiresAt time.Time
	Token     string
}

func (a *AuthUserCache) Get(key string) (*AuthUserData, bool) {
	a.lock.Lock()
	defer a.lock.Unlock()
	d, ok := a.Data[key]
	return &d, ok
}

func (a *AuthUserCache) Set(key string, d *AuthUserData) {
	a.lock.Lock()
	defer a.lock.Unlock()
	a.Data[key] = *d
}

func (a *AuthUserCache) Delete(key string) {
	a.lock.Lock()
	defer a.lock.Unlock()
	delete(a.Data, key)

	return
}
