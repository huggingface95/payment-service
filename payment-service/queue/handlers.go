package queue

import (
	"fmt"
	"payment-service/providers"
)

func HandleIBAN(paymentProvider providers.PaymentProvider, payload providers.IBANRequester) {
	// Вызов метода API провайдера для отправки запроса на генерацию IBAN
	response, err := paymentProvider.IBAN(payload) // Измените параметры в соответствии с интерфейсом PaymentProvider
	if err != nil {
		// Обработка ошибки при вызове метода для генерации IBAN
		fmt.Printf("Failed to generate IBAN: %v\n", err)
		return
	}

	// Обработка успешного ответа на генерацию IBAN
	fmt.Printf("Generated IBAN response: %v\n", response)
}

func HandlePayIn(paymentProvider providers.PaymentProvider, payload providers.PayInRequester) {
	// Вызов метода API провайдера для отправки запроса на PayIn
	response, err := paymentProvider.PayIn(payload)
	if err != nil {
		fmt.Printf("Failed to PayIn: %v\n", err)
		return
	}

	// Обработка успешного ответа на PayIn
	fmt.Printf("PayIn response: %v\n", response)
}

func HandlePayOut(paymentProvider providers.PaymentProvider, payload providers.PayOutRequester) {
	// Вызов метода API провайдера для отправки запроса на PayOut
	response, err := paymentProvider.PayOut(payload)
	if err != nil {
		fmt.Printf("Failed to PayOut: %v\n", err)
		return
	}

	// Обработка успешного ответа на PayOut
	fmt.Printf("PayOut response: %v\n", response)
}
