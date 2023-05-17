package queue

import (
	"encoding/json"
	"fmt"
	"payment-service/providers"
)

func getPaymentProvider(provider string) (providers.PaymentProvider, error) {
	switch provider {
	default:
		return nil, fmt.Errorf("unknown payment provider: %s", provider)
	}
}

func HandleIBAN(providersService *providers.Service, payload *IBANPayload) {
	paymentProvider, err := providersService.GetProvider()
	if err != nil {
		// обработка ошибки, связанной с неизвестным провайдером
		return
	}

	// используйте paymentProvider для выполнения действий, связанных с IBAN
	_ = paymentProvider
}

func HandlePayIn(providersService *providers.Service, payload *PayInPayload) {
	paymentProvider, err := providersService.GetProvider()
	if err != nil {
		// обработка ошибки, связанной с неизвестным провайдером
		return
	}

	// используйте paymentProvider для выполнения действий, связанных с PayIn
	_ = paymentProvider
}

func HandlePayOut(providersService *providers.Service, payload *PayOutPayload) {
	paymentProvider, err := providersService.GetProvider()
	if err != nil {
		// обработка ошибки, связанной с неизвестным провайдером
		return
	}

	// используйте paymentProvider для выполнения действий, связанных с PayOut
	_ = paymentProvider
}

func HandlePostBack(providersService *providers.Service, payload *json.RawMessage) {
	paymentProvider, err := providersService.GetProvider()
	if err != nil {
		// Обработка ошибки, связанной с неизвестным провайдером
		return
	}

	// Вызов метода PostBack провайдера
	_, err = (*paymentProvider).PostBack(payload) // Измените параметры в соответствии с интерфейсом PaymentProvider
	if err != nil {
		// Обработка ошибки при вызове PostBack провайдера
		return
	}
}
