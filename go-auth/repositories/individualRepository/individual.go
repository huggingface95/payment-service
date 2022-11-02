package individualRepository

import (
	"fmt"
	"github.com/eneoti/merge-struct"
	"gorm.io/gorm"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/requests/individual"
)

func FillIndividual(request individual.RegisterRequest) (user postgres.Individual, err error) {
	err = merp.MergeOverwrite(user, request, &user)

	return
}

func CreateIndividual(user *postgres.Individual) *gorm.DB {
	var company postgres.Company

	res := database.PostgresInstance.Where("name = ?", "Docu").
		Limit(1).
		Preload("CompanySetting.GroupRole").
		First(&company)
	if res.Error == nil {
		uRecord := database.PostgresInstance.Omit(
			"fullname", "language_id", "company_id", "citizenship_country_id",
			"birth_country_id", "applicant_status_id", "applicant_state_reason_id", "applicant_state_id",
			"applicant_risk_level_id", "account_manager_member_id").
			Create(user)

		if uRecord.Error == nil {
			fmt.Println(company.CompanySetting.GroupRole)

			return database.PostgresInstance.Create(&postgres.GroupRoleUser{
				GroupRoleId: company.CompanySetting.GroupRole.Id,
				UserId:      user.ID,
				UserType:    constants.ModelIndividual,
			})
		}
	}

	return nil
}
