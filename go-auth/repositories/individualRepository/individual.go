package individualRepository

import (
	"errors"
	"github.com/eneoti/merge-struct"
	"gorm.io/gorm"
	"jwt-authentication-golang/constants"
	postgres2 "jwt-authentication-golang/constants/postgres"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/requests/individual"
)

func fillIndividual(request individual.RegisterRequest) (user postgres.Individual, err error) {
	err = merp.MergeOverwrite(user, request, &user)
	user.TwoFactorAuthSettingId = 2
	user.IsVerificationPhone = postgres.ApplicantVerificationNotVerifyed
	user.IsVerificationEmail = postgres.ApplicantVerificationNotVerifyed

	return
}

func fillCompany(request individual.RegisterRequest) (applicantCompany postgres.ApplicantCompany, err error) {
	applicantCompany.Email = request.Email
	applicantCompany.Name = request.CompanyName
	applicantCompany.Url = request.Url
	return
}

func createWithTransaction(instance *gorm.DB, request individual.RegisterRequest) (err error, user postgres.Individual, company *postgres.Company) {
	user, err = fillIndividual(request)
	if err != nil {
		return
	}

	err = user.HashPassword(request.Password)

	if err != nil {
		return
	}

	applicantCompany, err := fillCompany(request)
	if err != nil {
		return
	}

	err = instance.Where("name = ?", "Docu").
		Limit(1).
		Preload("CompanyModules.Module").
		Preload("Project.Settings.GroupRole").
		First(&company).Error

	if err != nil {
		return
	} else if company.Project == nil {
		err = errors.New("in Company empty project")
		return
	} else if len(company.Project.Settings) == 0 {
		err = errors.New("in Company empty project settings")
		return
	} else if company.Project.Settings[0].GroupRole == nil {
		err = errors.New("in Company empty groupRole")
		return
	} else if len(company.CompanyModules) == 0 {
		err = errors.New("add modules in company")
		return
	}

	user.SetCompanyId(company.Id)
	err = instance.Omit(user.Omit()...).
		Create(&user).Error

	if err != nil {
		return
	}

	if request.ClientType == constants.RegisterClientTypeCorporate {
		err = instance.Create(&applicantCompany).Error
		if err != nil {
			return
		}
		var appCompanyForeign postgres.ApplicantCompanyForeign
		appCompanyForeign.IndividualId = user.ID
		appCompanyForeign.CompanyId = applicantCompany.ID
		appCompanyForeign.PositionId = 1
		appCompanyForeign.RelationId = 1
		err = instance.Create(&appCompanyForeign).Error
		if err != nil {
			return
		}
	}

	for _, pivotModule := range company.CompanyModules {
		instance.Create(&postgres.ApplicantIndividualModule{
			ApplicantIndividualId: user.ID,
			ModuleId:              pivotModule.Module.Id,
			IsActive:              pivotModule.IsActive,
		})
		if pivotModule.Module.Name != postgres2.KYC {

			instance.Create(&postgres.ApplicantModuleActivity{
				ApplicantId:   user.ID,
				ApplicantType: constants.ModelIndividual,
				ModuleId:      pivotModule.Module.Id,
				IsActive:      false,
			})
		}

	}

	return
}

func CreateIndividual(request individual.RegisterRequest) (err error, individual postgres.Individual, company *postgres.Company) {
	instance := database.PostgresInstance.Begin()

	err, individual, company = createWithTransaction(instance, request)

	if err != nil {
		instance.Rollback()
	} else {
		instance.Commit()
	}

	return
}
