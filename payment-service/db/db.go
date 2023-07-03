package db

import (
	"context"
	"fmt"
	"github.com/georgysavva/scany/pgxscan"
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

// Универсальные Методы для работы с таблицами

// Select заполняет переданный объект данными из таблицы table, которые соответствуют условиям wheres.
func (pg *Pg) Select(table string, columns []string, wheres map[string]interface{}, result ...interface{}) error {
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
	if err := row.Scan(result...); err != nil {
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

// Методы для работы с аккаунтами

// GetAccountOrderReferenceOrNil получает OrderReference аккаунта или nil по ID
func (pg *Pg) GetAccountOrderReferenceOrNil(accountID int) (*string, error) {
	var orderReference *string

	err := pg.Select("accounts", []string{"order_reference"}, map[string]interface{}{"id": accountID}, &orderReference)

	if err != nil {
		return nil, fmt.Errorf("unable to get OrderReference: %v", err)
	}

	return orderReference, nil
}

// GetAccountIBAN получает AccountNumber и IBAN по ID
func (pg *Pg) GetAccountIBAN(accountID int) (string, error) {
	columns := []string{"iban"}
	wheres := map[string]interface{}{"id": accountID}

	var iban string

	if err := pg.Select("accounts", columns, wheres, &iban); err != nil {
		return "", fmt.Errorf("unable to get account details: %v", err)
	}

	return iban, nil
}

// SetAccountCurrentBalance обновляет текущий баланс счета для указанного id счета.
func (pg *Pg) SetAccountCurrentBalance(accountId int, balance float64) error {
	updates := map[string]interface{}{"current_balance": balance}
	wheres := map[string]interface{}{"id": accountId}

	err := pg.Update("accounts", updates, wheres)
	if err != nil {
		return fmt.Errorf("unable to update account balance: %v", err)
	}

	return nil
}

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
		"account_number":   iban, // записываем IBAN в качестве account_number
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

// Методы для работы с транзакциями

// InsertTransaction создаёт новую запись в таблице transactions, используя общий метод Insert и возвращает её ID.
func (pg *Pg) InsertTransaction(transaction Transaction) (int, error) {
	params := map[string]interface{}{
		"company_id":         transaction.CompanyID,
		"currency_src_id":    transaction.CurrencySrcID,
		"currency_dst_id":    transaction.CurrencyDstID,
		"account_src_id":     transaction.AccountSrcID,
		"account_dst_id":     transaction.AccountDstID,
		"balance_prev":       transaction.BalancePrev,
		"balance_next":       transaction.BalanceNext,
		"amount":             transaction.Amount,
		"txtype":             transaction.TxType,
		"created_at":         transaction.CreatedAt,
		"updated_at":         transaction.UpdatedAt,
		"transfer_id":        transaction.TransferID,
		"transfer_type":      transaction.TransferType,
		"revenue_account_id": transaction.RevenueAccountID,
	}

	return pg.Insert("transactions", params)
}

// Методы для работы с входящими платежами

// InsertTransferIncoming вставляет новую запись о входящем переводе в базу данных и возвращает ее ID.
func (pg *Pg) InsertTransferIncoming(t *TransferIncoming) (int, error) {
	params := map[string]interface{}{
		"amount":                 t.Amount,
		"amount_debt":            t.AmountDebt,
		"currency_id":            t.CurrencyID,
		"status_id":              t.StatusID,
		"urgency_id":             t.UrgencyID,
		"operation_type_id":      t.OperationTypeID,
		"payment_provider_id":    t.PaymentProviderID,
		"payment_system_id":      t.PaymentSystemID,
		"payment_bank_id":        t.PaymentBankID,
		"payment_number":         t.PaymentNumber,
		"account_id":             t.AccountID,
		"recipient_id":           t.RecipientID,
		"recipient_type":         t.RecipientType,
		"company_id":             t.CompanyID,
		"system_message":         t.SystemMessage,
		"reason":                 t.Reason,
		"channel":                t.Channel,
		"bank_message":           t.BankMessage,
		"sender_account":         t.SenderAccount,
		"sender_bank_name":       t.SenderBankName,
		"sender_bank_address":    t.SenderBankAddress,
		"sender_bank_swift":      t.SenderBankSwift,
		"sender_bank_country_id": t.SenderBankCountryID,
		"sender_name":            t.SenderName,
		"sender_country_id":      t.SenderCountryID,
		"sender_city":            t.SenderCity,
		"sender_address":         t.SenderAddress,
		"sender_state":           t.SenderState,
		"sender_zip":             t.SenderZip,
		"respondent_fees_id":     t.RespondentFeesID,
		"execution_at":           t.ExecutionAt,
		"created_at":             t.CreatedAt,
		"updated_at":             t.UpdatedAt,
		"group_id":               t.GroupID,
		"group_type_id":          t.GroupTypeID,
		"project_id":             t.ProjectID,
		"price_list_id":          t.PriceListID,
		"price_list_fee_id":      t.PriceListFeeID,
		"beneficiary_type_id":    t.BeneficiaryTypeID,
		"beneficiary_name":       t.BeneficiaryName,
	}

	return pg.Insert("transfer_incomings", params)
}

// Методы для работы с платежами

// GetPaymentIDStatusNameAndAccountCurrentBalanceByPaymentNumber возвращает ID платежа, имя статуса и баланс аккаунта по PaymentNumber.
func (pg *Pg) GetPaymentIDStatusNameAndAccountCurrentBalanceByPaymentNumber(paymentNumber string) (*Payment, error) {
	query := `
		SELECT
			p.id, p.payment_number,
			s.id as "status.id", s.name as "status.name",
			a.id as "account.id", a.current_balance as "account.current_balance"
		FROM payments p
		JOIN payment_status s ON p.status_id = s.id
		JOIN accounts a ON p.account_id = a.id
		WHERE p.payment_number = $1
		LIMIT 1
	`

	rows, err := pg.pool.Query(context.Background(), query, paymentNumber)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	payment := &Payment{}
	if rows.Next() {
		err = pgxscan.ScanRow(payment, rows)
		if err != nil {
			return nil, err
		}
	}

	if err := rows.Err(); err != nil {
		return nil, err
	}

	return payment, nil
}

// SetPaymentAmountAndStatusByPaymentNumber обновляет сумму платежа и статус для заданного номера платежа.
func (pg *Pg) SetPaymentAmountAndStatusByPaymentNumber(paymentNumber string, amount float64, status string) error {
	updates := map[string]interface{}{
		"amount":    amount,
		"status_id": GetStatus(status),
	}

	wheres := map[string]interface{}{"payment_number": paymentNumber}

	err := pg.Update("payments", updates, wheres)
	if err != nil {
		return fmt.Errorf("unable to update payment amount and status: %v", err)
	}

	return nil
}
