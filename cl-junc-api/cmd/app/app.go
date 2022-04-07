package app

import (
	"cl-junc-api/internal/clearjunction"
	"cl-junc-api/internal/config"
	"cl-junc-api/internal/logger"
	"cl-junc-api/pkg/db"
	"encoding/json"
	"fmt"
	"github.com/julienschmidt/httprouter"
	"github.com/rs/zerolog"
	"net/http"
)

var Get = App{}

type App struct {
	Wire   *clearjunction.ClearJunction
	Redis  db.RedisDb
	Sql    db.Postgresql
	config config.Config
	Log    zerolog.Logger
}

func (a *App) Init() *App {
	a.config.Load()
	a.Log = logger.NewLog()
	a.Redis = db.NewRedisDb(a.config.Db.Redis)
	a.Sql = db.NewPostgresql(true, a.config.Db.Sql)
	a.Wire = clearjunction.New(a.config.Api.Clearjunction)

	return a
}

func (a *App) Index(w http.ResponseWriter, r *http.Request, _ httprouter.Params) {
	fmt.Fprint(w, "Welcome!\n")
}

func (a *App) Config() *config.Config {
	return &a.config
}

func (a *App) LogRedis(key string, data ...interface{}) bool {
	return a.Redis.AddList(key, fmt.Sprint(data...))
}

func (a *App) GetRedisList(key string, mc func() interface{}) []interface{} {
	list := a.Redis.LRange(key, 0, -1)
	newList := make([]interface{}, len(list))
	for _, v := range list {
		model := mc()
		err := json.Unmarshal([]byte(v), model)
		if err == nil {
			newList = append(newList, model)
		}
	}

	return newList
}
