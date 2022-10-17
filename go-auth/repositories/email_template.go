package repositories

import (
	"fmt"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
)

func GetEmailTemplateWithConditions(columns map[string]interface{}, likeColumns map[string]interface{}) *postgres.EmailTemplate {
	var template *postgres.EmailTemplate

	query := database.PostgresInstance.Limit(1)

	for column, value := range columns {
		query.Where(fmt.Sprintf("%s = ?", column), value)
	}

	for c, v := range likeColumns {
		query.Where(fmt.Sprintf("%s LIKE ?", c), fmt.Sprintf("%%%s%%", v))
	}

	query.First(&template)

	return template
}
