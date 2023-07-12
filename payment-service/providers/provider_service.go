package providers

import (
	"fmt"
	"github.com/go-playground/validator/v10"
	"github.com/spf13/viper"
)

// Service - сервис для управления провайдерами
type Service struct {
	Config        map[string]interface{}
	ProvidersList map[string]PaymentProvider
	Validator     *validator.Validate
}

// NewService - создает новый экземпляр Service
func NewService() *Service {
	service := &Service{
		Config:        viper.Get("providers").(map[string]interface{}),
		ProvidersList: map[string]PaymentProvider{},
		Validator:     validator.New(),
	}

	return service
}

// GetProvider - возвращает провайдера по его имени
func (ps *Service) GetProvider(providerName string) (PaymentProvider, error) {
	provider, ok := ps.ProvidersList[providerName]
	if !ok {
		return nil, fmt.Errorf("provider '%s' not found", providerName)
	}
	return provider, nil
}
