package config

import (
	cj "cl-junc-api/internal/clearjunction/models"
)

type Api struct {
	Clearjunction cj.Config `json:"clearjunction"`
}
