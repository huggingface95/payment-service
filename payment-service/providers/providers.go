package providers

// PaymentProvider - интерфейс для платежных провайдеров. И
type PaymentProvider interface {
	// Auth - авторизация у провайдера
	Auth(request AuthRequester) (AuthResponder, error)

	// IBAN - отправка запроса на генерацию IBAN
	IBAN(request IBANRequester) (IBANResponder, error)

	// PayIn - отправка запроса на получение платежа
	PayIn(request PayInRequester) (PayInResponder, error)

	// PayOut - отправка запроса на выплату
	PayOut(request PayOutRequester) (PayOutResponder, error)

	// Status - запрос состояния аккаунта
	Status(request StatusRequester) (StatusResponder, error)

	// PostBack - прием постбеков от провайдера с информацией о транзакции или генерируемом IBAN
	PostBack(request PostBackRequester) (PostBackResponder, error)

	// Custom - дополнительные специфические запросы провайдера
	Custom(request CustomRequester) (CustomResponder, error)
}

type AuthRequester interface {
}

type AuthResponder interface {
}

type IBANRequester interface {
}

type IBANResponder interface {
}

type PayInRequester interface {
}

type PayInResponder interface {
}

type PayOutRequester interface {
}

type PayOutResponder interface {
}

type StatusRequester interface {
}

type StatusResponder interface {
}

type PostBackRequester interface {
}

type PostBackResponder interface {
}

type CustomRequester interface {
}

type CustomResponder interface {
}
