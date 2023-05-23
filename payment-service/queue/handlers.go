package queue

import (
	"payment-service/providers"
)

func HandleIBAN(paymentProvider providers.PaymentProvider, payload *IBANPayload) {
	// используйте paymentProvider для выполнения действий, связанных с IBAN
}

func HandlePayIn(paymentProvider providers.PaymentProvider, payload *PayInPayload) {
	// используйте paymentProvider для выполнения действий, связанных с PayIn
}

func HandlePayOut(paymentProvider providers.PaymentProvider, payload *PayOutPayload) {
	// используйте paymentProvider для выполнения действий, связанных с PayOut
}

func HandleEmail(paymentProvider providers.PaymentProvider, payload *EmailPayload) {
	// Вызов метода PostBack провайдера
	_, err := paymentProvider.PostBack(payload) // Измените параметры в соответствии с интерфейсом PaymentProvider
	if err != nil {
		// Обработка ошибки при вызове PostBack провайдера
		return
	}
}
