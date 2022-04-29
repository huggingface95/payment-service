package app

import (
	"cl-junc-api/internal/clearjunction"
	"cl-junc-api/internal/config"
	"cl-junc-api/pkg/db"
	"cl-junc-api/pkg/utils/log"
	"encoding/json"
	"fmt"
)

var Get = App{}

type App struct {
	Wire   *clearjunction.ClearJunction
	Redis  db.RedisDb
	Sql    db.Postgresql
	config config.Config
}

func (a *App) Init() *App {
	a.config.Load()
	a.Redis = db.NewRedisDb(a.config.Db.Redis)
	a.Sql = db.NewPostgresql(true, a.config.Db.Sql)
	a.Wire = clearjunction.New(a.config.Api.Clearjunction, a.config.App.Url)

	return a
}

//func (a *App) Index(w http.ResponseWriter, r *http.Request, _ httprouter.Params) {
//	fmt.Fprint(w, "Welcome!\n")
//}

func (a *App) Config() *config.Config {
	return &a.config
}

func (a *App) LogRedis(key string, data ...interface{}) bool {
	return a.Redis.AddList(key, fmt.Sprint(data...))
}

func (a *App) GetRedisList(key string, mc func() interface{}) []interface{} {
	list := a.Redis.LRange(key, 0, -1)
	log.Debug().Msgf("jobs: GetRedisList: list: %#v", list)
	var newList []interface{}
	log.Debug().Msgf("jobs: GetRedisList: newList: %#v", newList)
	for _, v := range list {
		model := mc()
		log.Debug().Msgf("jobs: GetRedisList: model: %#v", model)
		err := json.Unmarshal([]byte(v), model)
		if err == nil {
			newList = append(newList, model)
		}
	}
	log.Debug().Msgf("jobs: GetRedisList: newList: %#v", newList)

	return newList
}
