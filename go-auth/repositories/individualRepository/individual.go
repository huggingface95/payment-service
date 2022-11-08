package individualRepository

import (
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
		user.SetCompanyId(company.Id)
		uRecord := database.PostgresInstance.Omit(user.Omit()...).
			Create(&user)

		if uRecord.Error == nil {
			return database.PostgresInstance.Create(&postgres.GroupRoleUser{
				GroupRoleId: company.CompanySetting.GroupRole.Id,
				UserId:      user.ID,
				UserType:    constants.ModelIndividual,
			})
		}
	}

	return nil
}
