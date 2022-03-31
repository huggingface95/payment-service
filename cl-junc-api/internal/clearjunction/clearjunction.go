package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
)

type ClearJunction struct {
	config models.Config
}

func New(config models.Config) *ClearJunction {
	return &ClearJunction{config: config}
}
