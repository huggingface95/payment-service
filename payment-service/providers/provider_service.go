package providers

import (
	"fmt"
	"path/filepath"
	"runtime"
	"strings"
)

// ProviderService - сервис для управления провайдерами
type ProviderService struct {
	providers map[string]PaymentProvider
}

// NewProviderService - создает новый экземпляр ProviderService
func NewProviderService(providers ...PaymentProvider) *ProviderService {
	ps := &ProviderService{
		providers: make(map[string]PaymentProvider),
	}

	for _, provider := range providers {
		ps.RegisterProvider(provider)
	}

	return ps
}

// RegisterProvider - регистрирует провайдера
func (ps *ProviderService) RegisterProvider(provider PaymentProvider) {
	providerName := getProviderName()
	ps.providers[providerName] = provider
}

// GetProvider - возвращает провайдера по его имени
func (ps *ProviderService) GetProvider(providerName string) (PaymentProvider, bool) {
	provider, ok := ps.providers[providerName]
	return provider, ok
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

// IBAN - генерирует IBAN с помощью провайдера
func (ps *ProviderService) IBAN(request IBANRequester) (IBANResponder, error) {
	// Получение провайдера из запроса
	providerName := getProviderName()
	provider, ok := ps.GetProvider(providerName)
	if !ok {
		return nil, fmt.Errorf("Provider '%s' not found", providerName)
	}

	// Вызов метода генерации IBAN у провайдера
	ibanResponse, err := provider.IBAN(request)
	if err != nil {
		return nil, fmt.Errorf("Failed to generate IBAN: %w", err)
	}

	return ibanResponse, nil
}
