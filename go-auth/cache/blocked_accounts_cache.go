package cache

import (
	"sync"
)

type BlockedAccountsCache struct {
	lock sync.Mutex
	Data map[string]int64 // Data should probably not have any reference fields
}

func (a *BlockedAccountsCache) Get(key string) (int64, bool) {
	a.lock.Lock()
	defer a.lock.Unlock()
	d, ok := a.Data[key]
	return d, ok
}

func (a *BlockedAccountsCache) Set(key string, d int64) {
	a.lock.Lock()
	defer a.lock.Unlock()
	a.Data[key] = d
	return
}

func (a *BlockedAccountsCache) Has(key string) bool {
	a.lock.Lock()
	defer a.lock.Unlock()
	_, ok := a.Data[key]
	return ok
}

func (a *BlockedAccountsCache) Delete(key string) {
	a.lock.Lock()
	defer a.lock.Unlock()
	delete(a.Data, key)

	return
}
