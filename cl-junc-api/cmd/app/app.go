package app

import (
	"cl-junc-api/internal/clearjunction"
	"cl-junc-api/internal/config"
	db2 "cl-junc-api/pkg/db"
	"fmt"
	"github.com/julienschmidt/httprouter"
	"net/http"
)

var Get = App{}

type App struct {
	Wire   *clearjunction.ClearJunction
	Redis  db2.RedisDb
	Sql    db2.Postgresql
	config config.Config
}

func (a *App) Init() *App {
	a.config.Load()

	a.Redis = db2.NewRedisDb(a.config.Db.Redis)
	a.Sql = db2.NewPostgresql(true, a.config.Db.Sql)
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
