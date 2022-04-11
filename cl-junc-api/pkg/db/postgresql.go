package db

import (
	"cl-junc-api/pkg/db/config"
	"cl-junc-api/pkg/utils/log"
	"context"
	"database/sql"
	"fmt"
	"github.com/uptrace/bun"
	"github.com/uptrace/bun/dialect/pgdialect"
	"github.com/uptrace/bun/driver/pgdriver"
	"github.com/uptrace/bun/extra/bundebug"
	"strings"
)

type Postgresql struct {
	client *bun.DB
}

// https://github.com/uptrace/bun/blob/master/example/rel-many-to-many/main.go
// https://github.com/uptrace/bun/blob/master/example/rel-many-to-many-self/main.go
// https://github.com/uptrace/bun/blob/master/example/migrate/main.go

// https://github.com/uptrace/bun/blob/master/example/placeholders/main.go
// https://github.com/uptrace/bun/blob/master/example/pg-faceted-search/main.go
// https://github.com/uptrace/bun/blob/master/example/model-hooks/main.go
func NewPostgresql(debug bool, config config.SqlDbConfig) Postgresql {
	dsn := fmt.Sprintf("postgres://%s:%s@%s:%d/%s?sslmode=disable", config.Username, config.Password, config.Server, config.Port, config.Database)
	sqldb := sql.OpenDB(pgdriver.NewConnector(pgdriver.WithDSN(dsn)))
	db := bun.NewDB(sqldb, pgdialect.New())

	//fmt.Println(db.Ping())
	//fmt.Println("sssssssssssss")

	if debug {
		db.AddQueryHook(bundebug.NewQueryHook(bundebug.WithVerbose(true)))
	}

	return Postgresql{client: db}
}

func (p *Postgresql) AddModel(model interface{}) (err error) {
	if _, err = p.client.NewCreateTable().Model(model).Exec(context.Background()); err != nil {
		panic(err)
	}
	return
}

func (p *Postgresql) Select(model interface{}) (result *bun.SelectQuery) {
	return p.client.NewSelect().Model(model)
}

func (p *Postgresql) SelectOne(model interface{}, res interface{}, column string) error {
	return p.client.NewSelect().ColumnExpr(column).Model(model).Limit(1).Scan(context.Background(), res)
}

func (p *Postgresql) Delete(model interface{}) (result *bun.DeleteQuery) {
	return p.client.NewDelete().Model(model)
}

func (p *Postgresql) Insert(model interface{}) (err error) {
	_, err = p.client.NewInsert().Model(model).Exec(context.Background())
	return
}

func (p *Postgresql) Update(model interface{}, updateFields ...string) (err error) {
	_, err = p.client.NewUpdate().Model(model).WherePK("id").Column(updateFields...).Exec(context.Background())
	return
}

func (p *Postgresql) UpdateAndSelect(model interface{}, updateFields ...string) (err error) {
	_, err = p.client.NewUpdate().Model(model).WherePK("id").Column(updateFields...).Returning("*").Exec(context.Background())
	return
}

func (p *Postgresql) SelectWhereResult(model interface{}, column string) error {
	return p.Select(model).WherePK(column).Scan(context.Background())
}

func (p *Postgresql) SelectWhereExistsResult(model interface{}, column string) (exists bool, err error) {
	err = p.SelectWhereResult(model, column)
	if err == nil {
		exists = true
	} else if strings.ContainsAny(err.Error(), "no rows in result set") {
		err = nil
	}
	return
}

func (p *Postgresql) SelectResult(model interface{}) error {
	return p.SelectWhereResult(model, "id")
}

func (p *Postgresql) SelectOneResult(model interface{}) error {
	return p.SelectWhereResult(model, "id")
}

func (p *Postgresql) SelectExistsResult(model interface{}) (exists bool, err error) {
	return p.SelectWhereExistsResult(model, "id")
}

func (p *Postgresql) SelectColumnValuesResult(model interface{}, columnName string) (result []string) {
	if err := p.Select(model).ColumnExpr(columnName).Scan(context.Background(), &result); err != nil {
		log.Error().Err(err)
		panic(err)
	}
	return
}

func (p *Postgresql) SelectMapResult(model interface{}) {
	if err := p.Select(model).Scan(context.Background()); err != nil {
		log.Error().Err(err)
		panic(err)
	}
}

func (p *Postgresql) DeleteResult(model interface{}, whereColumn string, whereValue interface{}) (sql.Result, error) {
	return p.Delete(model).Where(fmt.Sprint(whereColumn, " = ?"), whereValue).Exec(context.Background())
}

func (p *Postgresql) DeleteByIdResult(model interface{}, id int) (sql.Result, error) {
	return p.DeleteResult(model, "id", id)
}

func (p *Postgresql) Db() *bun.DB {
	return p.client
}

/*
	// Select a story and the associated author in a single query.
	story := new(Story)
	if err := db.NewSelect().
		Model(story).
		Relation("Author").
		Limit(1).
		Scan(ctx); err != nil {
		panic(err)
	}
*/
