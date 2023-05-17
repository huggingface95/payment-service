package providers

// PaymentProvider - интерфейс для платежных провайдеров
type PaymentProvider interface {
	// Auth - авторизация у провайдера
	Auth(request AuthRequester) (AuthResponder, error)

	// IBAN - отправка запроса на генерацию IBAN
	IBAN(request IBANRequester) (IBANResponder, error)

	// PayIn - отправка запроса на получение платежа
	PayIn(request PayInRequester) (PayInResponder, error)

	// PayOut - отправка запроса на выплату
	PayOut(request PayOutRequester) (PayOutResponder, error)

	// Status - запрос состояния транзакции
	Status(request StatusRequester) (StatusResponder, error)

	// PostBack - прием постбеков от провайдера с информацией о транзакции или генерируемом IBAN
	PostBack(request PostBackRequester) (PostBackResponder, error)
}

type AuthRequester interface {
	// Методы для получения данных из запроса Auth
}

type AuthResponder interface {
	// Методы для получения данных из ответа Auth
}

type IBANRequester interface {
	// Методы для получения данных из запроса IBAN
}

// IBANResponder Содержит методы для получения данных из ответа IBAN
type IBANResponder interface {
	GetIBANs() []string
}

type PayInRequester interface {
	// Методы для получения данных из запроса PayIn
}

type PayInResponder interface {
	// Методы для получения данных из ответа PayIn
}

type PayOutRequester interface {
	// Методы для получения данных из запроса PayOut
}

type PayOutResponder interface {
	// Методы для получения данных из ответа PayOut
}

type StatusRequester interface {
	// Методы для получения данных из запроса Status
}

type StatusResponder interface {
	// Методы для получения данных из ответа Status
}

type PostBackRequester interface {
	// Методы для получения данных из запроса PostBack
}

type PostBackResponder interface {
	// Методы для получения данных из ответа PostBack
}
