package queue

import (
	"payment-service/providers"
)

func HandleIBAN(paymentProvider providers.PaymentProvider, payload providers.IBANRequester) {
	// Вызов метода API провайдера для отправки запроса на генерацию IBAN
	_, err := paymentProvider.IBAN(payload) // Измените параметры в соответствии с интерфейсом PaymentProvider
	if err != nil {
		// Обработка ошибки при вызове метода для генерации IBAN
		return
	}
}

func HandlePayIn(paymentProvider providers.PaymentProvider, payload providers.PayInRequester) {
	// Вызов метода API провайдера для отправки запроса на получение платежа
	_, err := paymentProvider.PayIn(payload) // Измените параметры в соответствии с интерфейсом PaymentProvider
	if err != nil {
		// Обработка ошибки при вызове метода для получения платежа
		return
	}
}

func HandlePayOut(paymentProvider providers.PaymentProvider, payload providers.PayOutRequester) {
	// Вызов метода API провайдера для отправки запроса на выплату
	_, err := paymentProvider.PayOut(payload) // Измените параметры в соответствии с интерфейсом PaymentProvider
	if err != nil {
		// Обработка ошибки при вызове метода для выплаты
		return
	}
}
