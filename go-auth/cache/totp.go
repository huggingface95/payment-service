package cache

import (
	"sync"
)

type TotpCache struct {
	lock sync.Mutex
	Data map[string][]byte // Data should probably not have any reference fields
}

func (a *TotpCache) Get(key string) ([]byte, bool) {
	a.lock.Lock()
	defer a.lock.Unlock()
	d, ok := a.Data[key]
	return d, ok
}

func (a *TotpCache) Set(key string, d []byte) {
	a.lock.Lock()
	defer a.lock.Unlock()
	a.Data[key] = d
}

func (a *TotpCache) Delete(key string) {
	a.lock.Lock()
	defer a.lock.Unlock()
	delete(a.Data, key)

	return
}
