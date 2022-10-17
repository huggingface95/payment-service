package cache

import (
	"sync"
)

type LoginAttemptCache struct {
	lock sync.Mutex
	Data map[string]int
}

func (a *LoginAttemptCache) Get(key string) (int, bool) {
	a.lock.Lock()
	defer a.lock.Unlock()
	d, ok := a.Data[key]
	return d, ok
}

func (a *LoginAttemptCache) Set(key string, d int) {
	a.lock.Lock()
	defer a.lock.Unlock()
	a.Data[key] = d
}

func (a *LoginAttemptCache) Delete(key string) {
	a.lock.Lock()
	defer a.lock.Unlock()
	delete(a.Data, key)

	return
}
