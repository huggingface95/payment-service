package providers

import (
	"fmt"
	"github.com/spf13/viper"
	"path/filepath"
	"runtime"
	"strings"
)

// Service - сервис для управления провайдерами
type Service struct {
	Config    map[string]interface{}
	Current   *PaymentProvider
	providers map[string]*PaymentProvider
}

// NewService - создает новый экземпляр Service
func NewService() *Service {
	service := &Service{
		Config: viper.Get("providers").(map[string]interface{}),
	}

	return service
}

// IBAN - генерирует IBAN с помощью провайдера
func (ps *Service) IBAN(request IBANRequester) (IBANResponder, error) {
	provider, err := ps.GetProvider()
	if err != nil {
		return nil, err
	}

	// Вызов метода генерации IBAN у провайдера
	ibanResponse, err := (*provider).IBAN(request)
	if err != nil {
		return nil, fmt.Errorf("Failed to generate IBAN: %w", err)
	}

	return ibanResponse, nil
}

// UseProvider - устанавливает текущего провайдера по его имени
func (ps *Service) UseProvider(providerName string) {
	ps.Current = ps.providers[providerName]
}

// GetProvider - возвращает провайдера по его имени
func (ps *Service) GetProvider() (*PaymentProvider, error) {
	// Получение провайдера из запроса
	providerName := getProviderName()

	provider, ok := ps.providers[providerName]
	if !ok {
		return nil, fmt.Errorf("provider '%s' not found", providerName)
	}
	return provider, nil
}

func getProviderName() string {
	pc, _, _, _ := runtime.Caller(1)
	packageName := getPackageName(pc)
	return packageName
}

func getPackageName(pc uintptr) string {
	fullPackageName := runtime.FuncForPC(pc).Name()
	packageName := filepath.Base(strings.TrimSuffix(fullPackageName, filepath.Ext(fullPackageName)))
	return packageName
}
