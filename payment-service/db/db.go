package db

import (
	"context"
	"fmt"

	"github.com/jackc/pgx/v4"
)

type DB struct {
	conn *pgx.Conn
}

func NewDB(connStr string) (*DB, error) {
	conn, err := pgx.Connect(context.Background(), connStr)
	if err != nil {
		return nil, fmt.Errorf("unable to connect to database: %v", err)
	}
	return &DB{conn: conn}, nil
}

func (db *DB) Close() {
	db.conn.Close(context.Background())
}

// Функции для работы с транзакциями

// UpdateTransactionStatus обновляет статус транзакции.
func (db *DB) UpdateTransactionStatus(transactionID int, status string) error {
	ctx := context.Background()
	sql := "UPDATE transactions SET status=$1, updated_at=NOW() WHERE id=$2"
	tag, err := db.conn.Exec(ctx, sql, status, transactionID)
	if err != nil {
		return fmt.Errorf("unable to update transaction status: %v", err)
	}
	if tag.RowsAffected() != 1 {
		return fmt.Errorf("expected one row to be affected, got %d", tag.RowsAffected())
	}
	return nil
}

// InsertTransaction создает новую транзакцию и возвращает ее ID.
func (db *DB) InsertTransaction(providerID int, clientOrder, transactionType, status, currency string, amount float64) (int, error) {
	ctx := context.Background()
	sql := `INSERT INTO transactions (provider_id, client_order, status, transaction_type, amount, currency, created_at, updated_at)
			VALUES ($1, $2, $3, $4, $5, $6, NOW(), NOW()) RETURNING id`
	var id int
	err := db.conn.QueryRow(ctx, sql, providerID, clientOrder, status, transactionType, amount, currency).Scan(&id)
	if err != nil {
		return 0, fmt.Errorf("unable to insert transaction: %v", err)
	}
	return id, nil
}

// GetTransaction возвращает транзакцию по ее ID.
func (db *DB) GetTransaction(transactionID int) (Transaction, error) {
	ctx := context.Background()
	sql := `SELECT id, provider_id, client_order, status, transaction_type, amount, currency, created_at, updated_at
			FROM transactions WHERE id=$1`
	var transaction Transaction
	err := db.conn.QueryRow(ctx, sql, transactionID).Scan(&transaction.ID, &transaction.ProviderID, &transaction.ClientOrder, &transaction.Status, &transaction.TransactionType, &transaction.Amount, &transaction.Currency, &transaction.CreatedAt, &transaction.UpdatedAt)
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
func (db *DB) InsertIBAN(transactionID int, ibanNumber, ibanCountry string) (int, error) {
	ctx := context.Background()
	sql := "INSERT INTO ibans (transaction_id, iban_number, iban_country, created_at) VALUES ($1, $2, $3, NOW()) RETURNING id"
	var id int
	err := db.conn.QueryRow(ctx, sql, transactionID, ibanNumber, ibanCountry).Scan(&id)
	if err != nil {
		return 0, fmt.Errorf("unable to insert IBAN: %v", err)
	}
	return id, nil
}

// GetIBAN возвращает IBAN по его ID.
func (db *DB) GetIBAN(ibanID int) (IBAN, error) {
	ctx := context.Background()
	sql := "SELECT id, transaction_id, iban_number, iban_country, created_at FROM ibans WHERE id=$1"
	var iban IBAN
	err := db.conn.QueryRow(ctx, sql, ibanID).Scan(&iban.ID, &iban.TransactionID, &iban.IBANNumber, &iban.IBANCountry, &iban.CreatedAt)
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
func (db *DB) GetProvider(providerID int) (Provider, error) {
	ctx := context.Background()
	sql := "SELECT id, name, api_key, api_url FROM providers WHERE id=$1"
	var provider Provider
	err := db.conn.QueryRow(ctx, sql, providerID).Scan(&provider.ID, &provider.Name, &provider.APIKey, &provider.APIURL)
	if err != nil {
		if err == pgx.ErrNoRows {
			return Provider{}, fmt.Errorf("provider not found")
		}
		return Provider{}, fmt.Errorf("unable to get provider: %v", err)
	}
	return provider, nil
}

// GetAllProviders возвращает список всех провайдеров.
func (db *DB) GetAllProviders() ([]Provider, error) {
	ctx := context.Background()
	sql := "SELECT id, name, api_key, api_url FROM providers"
	rows, err := db.conn.Query(ctx, sql)
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

func (db *DB) UpdateAccount(account *Account, pkField string, updateFields map[string]interface{}) error {
	ctx := context.Background()

	// Формируем SQL-запрос для обновления полей
	sql := "UPDATE accounts SET "
	var values []interface{}
	i := 1
	for field, value := range updateFields {
		sql += field + "=$" + fmt.Sprint(i) + ", "
		values = append(values, value)
		i++
	}
	sql = sql[:len(sql)-2] + " WHERE " + pkField + "=$" + fmt.Sprint(i)
	values = append(values, account.ID)

	// Выполняем SQL-запрос
	_, err := db.conn.Exec(ctx, sql, values...)
	if err != nil {
		return fmt.Errorf("unable to update account: %v", err)
	}

	return nil
}
