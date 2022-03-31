package banking

import (
	"cl-junc-api/internal/banking/models"
)

type Banking struct {
	config models.Config
}

func New(config models.Config) *Banking {
	return &Banking{config: config}
}
