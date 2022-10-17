package cache

import (
	"sync"
)

type TwoFactorAttemptCache struct {
	lock sync.Mutex
	Data map[string]int // Data should probably not have any reference fields
}

func (a *TwoFactorAttemptCache) Get(key string) (int, bool) {
	a.lock.Lock()
	defer a.lock.Unlock()
	d, ok := a.Data[key]
	return d, ok
}

func (a *TwoFactorAttemptCache) Set(key string, d int) {
	a.lock.Lock()
	defer a.lock.Unlock()
	a.Data[key] = d
}

func (a *TwoFactorAttemptCache) Delete(key string) {
	a.lock.Lock()
	defer a.lock.Unlock()
	delete(a.Data, key)

	return
}
