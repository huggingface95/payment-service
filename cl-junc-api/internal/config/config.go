package config

import (
	"cl-junc-api/pkg/config"
	"encoding/json"
	"fmt"
	"io/ioutil"
	"os"
	"path/filepath"
)

type Config struct {
	App config.App `json:"app"`
	Api Api        `json:"api"`
	Db  Db         `json:"db"`
	pwd string
}

func (c *Config) PostBackUrl() string {
	return c.App.Url + "postback/"
}

func (c *Config) Load() *Config {
	c.pwd, _ = os.Getwd()

	data, err := ioutil.ReadFile(filepath.Join(c.pwd, "/config.json"))

	if err != nil {
		fmt.Println(err)
		os.Exit(1)
	}

	json.Unmarshal(data, c)
	return c
}
