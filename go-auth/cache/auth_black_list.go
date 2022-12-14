package cache

import (
	"sync"
)

type BlackListCache struct {
	lock sync.Mutex
	Data []BlackListData
}

type BlackListData struct {
	Forever bool
	Token   string
	Id      uint64
}

func (b *BlackListCache) Get(key uint64, token string) int {
	b.lock.Lock()
	defer b.lock.Unlock()

	for id, val := range b.Data {
		if val.Id == key && val.Token == token {
			return id
		}
	}

	return 0
}

func (b *BlackListCache) Set(d *BlackListData) {
	b.lock.Lock()
	defer b.lock.Unlock()
	b.Data = append(b.Data, *d)

	return
}
