package db

import (
	"context"
	"fmt"
	"github.com/jackc/pgx/v4"
	"github.com/jackc/pgx/v4/pgxpool"
	"strings"
	"time"
)

type Pg struct {
	pool *pgxpool.Pool
}

func NewDB(connStr string) (*Pg, error) {
	pool, err := pgxpool.Connect(context.Background(), connStr)
	if err != nil {
		return nil, fmt.Errorf("ошибка при создании пула подключений: %v", err)
	}
	return &Pg{pool: pool}, nil
}

func (pg *Pg) Close() {
	pg.pool.Close()
}

// Универсальные функции для работы с таблицами

// Select заполняет переданный объект данными из таблицы table, которые соответствуют условиям wheres.
func (pg *Pg) Select(table string, columns []string, wheres map[string]interface{}, result interface{}) error {
	ctx := context.Background()

	columnList := "*"
	if len(columns) > 0 {
		columnList = strings.Join(columns, ", ")
	}

	// Формируем SQL-запрос для выбора записей
	sql := fmt.Sprintf("SELECT %s FROM %s WHERE ", columnList, table)
	var values []interface{}
	i := 1
	for where, value := range wheres {
		sql += where + "=$" + fmt.Sprint(i) + " AND "
		values = append(values, value)
		i++
	}
	sql = sql[:len(sql)-5] // Удаляем последний " AND "

	// Выполняем SQL-запрос
	row := pg.pool.QueryRow(ctx, sql, values...)

	// Заполняем переданный объект
	if err := row.Scan(result); err != nil {
		if err == pgx.ErrNoRows {
			return fmt.Errorf("no rows were returned")
		}
		return fmt.Errorf("unable to select records: %v", err)
	}

	return nil
}

// Insert создает новую запись в таблице table с параметрами params и возвращает ее ID.
func (pg *Pg) Insert(table string, params map[string]interface{}) (int, error) {
	ctx := context.Background()

	columns := make([]string, 0)
	values := make([]interface{}, 0)
	index := 1

	for key, value := range params {
		columns = append(columns, key)
		values = append(values, value)
		index++
	}

	sql := fmt.Sprintf("INSERT INTO %s (%s) VALUES ", table, strings.Join(columns, ", "))
	placeholders := make([]string, len(values))
	for i := range placeholders {
		placeholders[i] = fmt.Sprintf("$%d", i+1)
	}
	sql += fmt.Sprintf("(%s) RETURNING id", strings.Join(placeholders, ", "))

	var id int
	err := pg.pool.QueryRow(ctx, sql, values...).Scan(&id)
	if err != nil {
		return 0, fmt.Errorf("unable to insert transaction: %v", err)
	}

	return id, nil
}

// Update обновляет поля updates таблицы table по условиям wheres.
func (pg *Pg) Update(table string, updates map[string]interface{}, wheres map[string]interface{}) error {
	ctx := context.Background()

	// Формируем SQL-запрос для обновления полей
	sql := fmt.Sprintf("UPDATE %s SET ", table)
	var values []interface{}
	i := 1
	for field, value := range updates {
		sql += field + "=$" + fmt.Sprint(i) + ", "
		values = append(values, value)
		i++
	}
	sql = sql[:len(sql)-2] + " WHERE "
	for where, value := range wheres {
		sql += where + "=$" + fmt.Sprint(i) + " AND "
		values = append(values, value)
		i++
	}
	sql = sql[:len(sql)-5] // Удаляем последний " AND "

	// Выполняем SQL-запрос
	result, err := pg.pool.Exec(ctx, sql, values...)
	if err != nil {
		return fmt.Errorf("unable to update account: %v", err)
	}

	rowsAffected := result.RowsAffected()
	if rowsAffected == 0 {
		return fmt.Errorf("no rows were affected by the update")
	}

	return nil
}

// Функции для работы с аккаунтами

// SetAccountStateToWaitingForAccountIbanGeneration устанавливает поле AccountStateID для заданного аккаунта.
func (pg *Pg) SetAccountStateToWaitingForAccountIbanGeneration(accountID int) error {
	updates := map[string]interface{}{
		"account_state_id": AccountStateWaitingForAccountIbanGeneration, // устанавливаем статус аккаунта
		"updated_at":       time.Now(),                                  // обновляем поле updated_at
	}

	wheres := map[string]interface{}{
		"id": accountID,
	}

	return pg.Update("accounts", updates, wheres)
}

// SetAccountOrderReferenceAndStateToWaitingForApproval обновляет OrderReference и State для заданного аккаунта.
func (pg *Pg) SetAccountOrderReferenceAndStateToWaitingForApproval(accountID int, orderReference string) error {
	updates := map[string]interface{}{
		"order_reference":  orderReference,
		"account_state_id": AccountStateWaitingForApproval, // устанавливаем статус аккаунта
		"updated_at":       time.Now(),                     // обновляем поле updated_at
	}

	wheres := map[string]interface{}{
		"id": accountID,
	}

	return pg.Update("accounts", updates, wheres)
}

// SetAccountIBANAndStateToActiveByOrderReference обновляет IBAN и State для заданного аккаунта на основе OrderReference.
func (pg *Pg) SetAccountIBANAndStateToActiveByOrderReference(orderReference string, iban string) error {
	updates := map[string]interface{}{
		"iban":             iban,
		"account_state_id": AccountStateActive,
		"updated_at":       time.Now(), // обновляем поле updated_at
	}

	wheres := map[string]interface{}{
		"order_reference": orderReference,
	}

	return pg.Update("accounts", updates, wheres)
}

// SetAccountStateToRejectedByOrderReference обновляет поле State для заданного аккаунта на основе OrderReference.
func (pg *Pg) SetAccountStateToRejectedByOrderReference(orderReference string) error {
	updates := map[string]interface{}{
		"account_state_id": AccountStateRejected,
		"updated_at":       time.Now(), // обновляем поле updated_at
	}

	wheres := map[string]interface{}{
		"order_reference": orderReference,
	}

	return pg.Update("accounts", updates, wheres)
}

// Функции для работы с транзакциями

// UpdateTransactionStatus обновляет статус транзакции.
func (pg *Pg) UpdateTransactionStatus(transactionID int, status string) error {
	ctx := context.Background()
	sql := "UPDATE transactions SET status=$1, updated_at=NOW() WHERE id=$2"
	tag, err := pg.pool.Exec(ctx, sql, status, transactionID)
	if err != nil {
		return fmt.Errorf("unable to update transaction status: %v", err)
	}
	if tag.RowsAffected() != 1 {
		return fmt.Errorf("expected one row to be affected, got %d", tag.RowsAffected())
	}
	return nil
}

// GetTransaction возвращает транзакцию по ее ID.
func (pg *Pg) GetTransaction(transactionID int) (Transaction, error) {
	ctx := context.Background()
	sql := `SELECT id, provider_id, client_order, status, transaction_type, amount, currency, created_at, updated_at
			FROM transactions WHERE id=$1`
	var transaction Transaction
	err := pg.pool.QueryRow(ctx, sql, transactionID).Scan(&transaction.ID, &transaction.ProviderID, &transaction.ClientOrder, &transaction.Status, &transaction.TransactionType, &transaction.Amount, &transaction.Currency)
	if err != nil {
		if err == pgx.ErrNoRows {
			return Transaction{}, fmt.Errorf("transaction not found")
		}
		return Transaction{}, fmt.Errorf("unable to get transaction: %v", err)
	}
	return transaction, nil
}

// Функции для работы с IBAN

// InsertIBAN создает новый IBAN и возвращает его ID.
func (pg *Pg) InsertIBAN(transactionID int, ibanNumber, ibanCountry string) (int, error) {
	ctx := context.Background()
	sql := "INSERT INTO ibans (transaction_id, iban_number, iban_country, created_at) VALUES ($1, $2, $3, NOW()) RETURNING id"
	var id int
	err := pg.pool.QueryRow(ctx, sql, transactionID, ibanNumber, ibanCountry).Scan(&id)
	if err != nil {
		return 0, fmt.Errorf("unable to insert IBAN: %v", err)
	}
	return id, nil
}

// GetIBAN возвращает IBAN по его ID.
func (pg *Pg) GetIBAN(ibanID int) (IBAN, error) {
	ctx := context.Background()
	sql := "SELECT id, transaction_id, iban_number, iban_country, created_at FROM ibans WHERE id=$1"
	var iban IBAN
	err := pg.pool.QueryRow(ctx, sql, ibanID).Scan(&iban.ID, &iban.TransactionID, &iban.IBANNumber, &iban.IBANCountry, &iban.CreatedAt)
	if err != nil {
		if err == pgx.ErrNoRows {
			return IBAN{}, fmt.Errorf("IBAN not found")
		}
		return IBAN{}, fmt.Errorf("unable to get IBAN: %v", err)
	}
	return iban, nil
}

// Функции для работы с провайдерами

// GetProvider возвращает провайдера по его ID.
func (pg *Pg) GetProvider(providerID int) (Provider, error) {
	ctx := context.Background()
	sql := "SELECT id, name, api_key, api_url FROM providers WHERE id=$1"
	var provider Provider
	err := pg.pool.QueryRow(ctx, sql, providerID).Scan(&provider.ID, &provider.Name, &provider.APIKey, &provider.APIURL)
	if err != nil {
		if err == pgx.ErrNoRows {
			return Provider{}, fmt.Errorf("provider not found")
		}
		return Provider{}, fmt.Errorf("unable to get provider: %v", err)
	}
	return provider, nil
}

// GetAllProviders возвращает список всех провайдеров.
func (pg *Pg) GetAllProviders() ([]Provider, error) {
	ctx := context.Background()
	sql := "SELECT id, name, api_key, api_url FROM providers"
	rows, err := pg.pool.Query(ctx, sql)
	if err != nil {
		return nil, fmt.Errorf("unable to get providers: %v", err)
	}
	defer rows.Close()

	providers := make([]Provider, 0)
	for rows.Next() {
		var provider Provider
		err := rows.Scan(&provider.ID, &provider.Name, &provider.APIKey, &provider.APIURL)
		if err != nil {
			return nil, fmt.Errorf("unable to scan provider: %v", err)
		}
		providers = append(providers, provider)
	}
	return providers, nil
}

// Функции для работы с платежами

// GetPaymentWithRelations возвращает платеж со связанными сущностями на основе указанных отношений и условий.
func (pg *Pg) GetPaymentWithRelations(relations []string, wheres map[string]interface{}) (payment *Payment, err error) {
	query := `SELECT p.* FROM payments p %s WHERE %s LIMIT 1`

	// Собираем фрагменты SQL-запроса для связанных сущностей
	joins := ""
	for _, relation := range relations {
		switch relation {
		case "Account.Payee":
			joins += `
				LEFT JOIN accounts a ON p.account_id = a.id
				LEFT JOIN payees py ON a.payee_id = py.id
			`
		case "Status":
			joins += `
				LEFT JOIN payment_status s ON p.status_id = s.id
			`
		case "Provider":
			joins += `
				LEFT JOIN payment_provider pr ON p.payment_provider_id = pr.id
			`
		case "OperationType":
			joins += `
				LEFT JOIN payment_types t ON p.type_id = t.id
			`
		}
	}

	// Формируем фрагмент SQL-запроса для условий
	conditions := ""
	values := make([]interface{}, 0)
	i := 1
	for field, value := range wheres {
		conditions += fmt.Sprintf("%s=$%d AND ", field, i)
		values = append(values, value)
		i++
	}
	conditions = conditions[:len(conditions)-5] // Удаляем последний " AND "

	// Формируем окончательный SQL-запрос
	sql := fmt.Sprintf(query, joins, conditions)

	row := pg.pool.QueryRow(context.Background(), sql, values...)

	// Используем pgx.Row.ScanStruct для прямого сканирования результатов в структуру
	err = row.Scan(payment)
	if err != nil {
		if err == pgx.ErrNoRows {
			return nil, fmt.Errorf("ничего не найдено: %v", err)
		}
		return nil, err
	}

	return payment, nil
}
