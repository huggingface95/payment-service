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

func FillCompany(request individual.RegisterRequest) (user postgres.ApplicantCompany, err error) {
	user.Email = request.Email
	user.Name = request.CompanyName
	user.Url = request.Url
	return
}

func CreateIndividual(user *postgres.Individual, applicantCompany *postgres.ApplicantCompany, clientType string) *gorm.DB {
	var company postgres.Company

	res := database.PostgresInstance.Where("name = ?", "Docu").
		Limit(1).
		Preload("CompanySetting.GroupRole").
		First(&company)

	if company.CompanySetting == nil || company.CompanySetting.GroupRole == nil {
		return nil
	}

	if res.Error == nil {
		user.SetCompanyId(company.Id)
		uRecord := database.PostgresInstance.Omit(user.Omit()...).
			Create(&user)

		if uRecord.Error == nil {
			if clientType == constants.RegisterClientTypeCorporate {
				appCompanyRec := database.PostgresInstance.Create(&applicantCompany)
				if appCompanyRec.Error != nil {
					return nil
				}
				var appCompanyForeign postgres.ApplicantCompanyForeign
				appCompanyForeign.IndividualId = user.ID
				appCompanyForeign.CompanyId = applicantCompany.ID
				appCompanyForeign.PositionId = 1
				appCompanyForeign.RelationId = 1
				appCompanyForeignRec := database.PostgresInstance.Create(&appCompanyForeign)
				if appCompanyForeignRec.Error != nil {
					return nil
				}
			}

			return database.PostgresInstance.Create(&postgres.GroupRoleUser{
				GroupRoleId: company.CompanySetting.GroupRole.Id,
				UserId:      user.ID,
				UserType:    constants.ModelIndividual,
			})
		}
	}

	return nil
}
