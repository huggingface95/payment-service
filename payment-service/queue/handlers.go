package queue

import (
	"fmt"
	"payment-service/providers"
)

func getPaymentProvider(provider string) (providers.PaymentProvider, error) {
	switch provider {
	default:
		return nil, fmt.Errorf("unknown payment provider: %s", provider)
	}
}

func HandleIBAN(provider string, payload *IBANPayload) {
	paymentProvider, err := getPaymentProvider(provider)
	if err != nil {
		// обработка ошибки, связанной с неизвестным провайдером
		return
	}

	// используйте paymentProvider для выполнения действий, связанных с IBAN
	_ = paymentProvider
}

func HandlePayIn(provider string, payload *PayInPayload) {
	paymentProvider, err := getPaymentProvider(provider)
	if err != nil {
		// обработка ошибки, связанной с неизвестным провайдером
		return
	}

	// используйте paymentProvider для выполнения действий, связанных с PayIn
	_ = paymentProvider
}

func HandlePayOut(provider string, payload *PayOutPayload) {
	paymentProvider, err := getPaymentProvider(provider)
	if err != nil {
		// обработка ошибки, связанной с неизвестным провайдером
		return
	}

	// используйте paymentProvider для выполнения действий, связанных с PayOut
	_ = paymentProvider
}
