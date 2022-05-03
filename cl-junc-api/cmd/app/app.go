package app

import (
	"cl-junc-api/internal/clearjunction"
	"cl-junc-api/internal/config"
	db2 "cl-junc-api/internal/db"
	"cl-junc-api/pkg/db"
	"cl-junc-api/pkg/utils/log"
	"encoding/json"
	"fmt"
	"time"
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

func (a *App) GetRedisDataByBlPop(key string, mc func() interface{}) interface{} {
	row := a.Redis.BLPop(time.Second, key)

	model := mc()

	if len(row) < 2 {
		return nil
	}
	err := json.Unmarshal([]byte(row[1]), model)
	if err != nil {
		log.Error().Err(err)
		return nil
	}

	return model
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

func (a *App) GetStatusByName(name string) *db2.Status {
	status := &db2.Status{
		Name: name,
	}
	err := a.Sql.SelectWhereResult(status, "name")
	if err != nil {
		log.Debug().Msgf("DON'T find status")
		panic(err)
	}

	return status
}

func (a *App) GetPaymentWithRelations(payment *db2.Payment, relations []string, column string) *db2.Payment {
	err := a.Sql.SelectWhereWithRelationResult(payment, relations, column)

	if err != nil {
		log.Error().Err(err)
		panic(err)
	}

	return payment
}

func (a *App) GetPayment(payment *db2.Payment, column string) *db2.Payment {
	err := a.Sql.SelectWhereResult(payment, column)

	if err != nil {
		log.Error().Err(err)
		panic(err)
	}

	return payment
}

func (a *App) GetPayee(payee *db2.Payee, column string) *db2.Payee {
	err := a.Sql.SelectWhereResult(payee, column)

	if err != nil {
		log.Error().Err(err)
		panic(err)
	}

	return payee
}

func (a *App) UpdatePayment(payment *db2.Payment, search string, fields ...string) bool {
	err := a.Sql.Update(payment, search, fields...)
	if err != nil {
		log.Error().Err(err)
		panic(err)
	}
	return true
}

func (a *App) UpdateAccount(account *db2.Account, search string, fields ...string) bool {
	err := a.Sql.Update(account, search, fields...)
	if err != nil {
		log.Error().Err(err)
		panic(err)
	}
	return true
}

func (a *App) CreateTransaction(tr *db2.Transaction) *db2.Transaction {
	err := a.Sql.Insert(tr)
	if err != nil {
		log.Error().Err(err)
		panic(err)
	}
	return tr
}
