package utils

import (
	"fmt"
	"strings"
	"time"
)

type ISOExtendedDate time.Time

// UnmarshalJSON расшифровывает JSON
func (r *ISOExtendedDate) UnmarshalJSON(b []byte) error {
	value := strings.Trim(string(b), `"`) // Избавляемся от кавычек
	if value == "null" || value == "" {
		return nil
	}

	parsedTime, err := time.Parse("2006-01-02", value) // Разбираем время
	if err != nil {
		return err
	}

	*r = ISOExtendedDate(parsedTime) // Задаем результат, используя указатель
	return nil
}

// MarshalJSON преобразует время в JSON
func (r *ISOExtendedDate) MarshalJSON() ([]byte, error) {
	return []byte(fmt.Sprintf(`"%s"`, time.Time(*r).Format("2006-01-02"))), nil
}
