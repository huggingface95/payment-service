package individualRepository

import (
	"crypto/sha1"
	"encoding/base64"
	"errors"
	"fmt"
	merp "github.com/eneoti/merge-struct"
	"gorm.io/gorm"
	"jwt-authentication-golang/constants"
	postgres2 "jwt-authentication-golang/constants/postgres"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/requests/individual"
)

func fillIndividual(request individual.RegisterApplicantInterface) (user postgres.Individual, err error) {

	if request.GetType() == individual.RegisterPrivate {
		err = merp.MergeOverwrite(user, request.(*individual.RegisterRequestPrivate).RegisterRequestApplicant, &user)
	} else if request.GetType() == individual.RegisterCorporate {
		err = merp.MergeOverwrite(user, request.(*individual.RegisterRequestCorporate).RegisterRequestApplicant, &user)
	}
	err = merp.MergeOverwrite(user, request, &user)
	user.TwoFactorAuthSettingId = 2
	user.IsVerificationPhone = postgres.ApplicantVerificationNotVerifyed
	user.IsVerificationEmail = postgres.ApplicantVerificationNotVerifyed
	user.CountryId = request.GetCountryId()
	return
}

func fillCompany(request individual.RegisterApplicantInterface, company *postgres.Company) (applicantCompany postgres.ApplicantCompany, err error) {
	applicantCompany.Email = request.GetEmail()
	applicantCompany.Name = request.GetCompanyName()
	applicantCompany.Url = request.GetUrl()
	applicantCompany.CompanyId = company.Id
	applicantCompany.CountryId = request.GetCountryId()
	applicantCompany.IsVerificationEmail = postgres.ApplicantVerificationNotVerifyed
	applicantCompany.IsActive = postgres.ApplicantStateSuspended
	return
}

func fillApplicantCompanyForeign(user postgres.Individual, applicantCompany postgres.ApplicantCompany) (appCompanyForeign postgres.ApplicantIndividualCompany) {
	appCompanyForeign.ApplicantId = user.Id
	appCompanyForeign.ApplicantCompanyId = applicantCompany.Id
	appCompanyForeign.PositionId = 1
	appCompanyForeign.RelationId = 1
	appCompanyForeign.ApplicantType = constants.ModelIndividual
	return
}

func createWithTransaction(instance *gorm.DB, request individual.RegisterApplicantInterface) (err error, user postgres.Individual, company *postgres.Company) {
	user, err = fillIndividual(request)
	if err != nil {
		return
	}

	err = user.HashPassword(request.GetPassword())

	if err != nil {
		return
	}

	err = instance.Where("id = ?", request.GetCompanyId()).
		Limit(1).
		Preload("CompanyModules.Module").
		Preload("Project", func(db *gorm.DB) *gorm.DB {
			return db.
				Order("projects.created_at").
				Preload("Settings", "applicant_type = ?", request.GetApplicantModel(), func(db *gorm.DB) *gorm.DB {
					return db.Preload("GroupRole")
				})
		}).
		First(&company).Error

	if err != nil {
		return
	}

	if company.Project == nil {
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

	if request.GetType() != individual.RegisterStandard && company.Project.Id != request.GetProjectId() {
		err = errors.New("wrong project")
		return
	}

	applicantCompany, err := fillCompany(request, company)
	if err != nil {
		return
	}

	user.SetCompanyId(company.Id)
	err = instance.Omit(user.Omit()...).
		Create(&user).Error

	if err != nil {
		return
	}

	isIndividual := true
	isCorporate := false
	if request.GetType() == individual.RegisterCorporate ||
		(request.GetType() == individual.RegisterStandard && request.(*individual.RegisterRequest).ClientType == constants.RegisterClientTypeCorporate) {
		isIndividual = false
		isCorporate = true

		err = instance.Omit(applicantCompany.Omit()...).Create(&applicantCompany).Error
		if err != nil {
			return
		}

		appCompanyForeign := fillApplicantCompanyForeign(user, applicantCompany)
		err = instance.Create(&appCompanyForeign).Error
		if err != nil {
			return
		}
	}

	for _, pivotModule := range company.CompanyModules {
		instance.Create(&postgres.ApplicantIndividualModule{
			ApplicantIndividualId: user.Id,
			ModuleId:              pivotModule.Module.Id,
			IsActive:              pivotModule.IsActive,
		})
		if pivotModule.Module.Name != postgres2.KYC {
			instance.Create(&postgres.ApplicantModuleActivity{
				ApplicantId: user.Id,
				ModuleId:    pivotModule.Module.Id,
				Individual:  isIndividual,
				Corporate:   isCorporate,
			})
		}

	}

	return
}

func CreateIndividual(request individual.RegisterApplicantInterface) (err error, individual postgres.Individual, company *postgres.Company) {
	instance := database.PostgresInstance.Begin()

	err, individual, company = createWithTransaction(instance, request)

	if err != nil {
		instance.Rollback()
	} else {
		instance.Commit()
	}

	return
}

func UpdateProjectHash(setting *postgres.ProjectSettings) *gorm.DB {
	return database.PostgresInstance.Save(setting)
}

func GenerateSignHash(secret string, cId uint64, pId uint64) string {
	stringHash := fmt.Sprintf("%s%d%d", secret, cId, pId)
	hasher := sha1.New()
	hasher.Write([]byte(stringHash))
	return base64.URLEncoding.EncodeToString(hasher.Sum(nil))
}

func CheckAndParseInternalIndividualSign(hash string) (bool, uint64, uint64) {
	var setting postgres.ProjectSettings
	exists := database.PostgresInstance.
		Preload("Project").
		Where("hash = ?", hash).
		Limit(1).Find(&setting)

	if exists.RowsAffected > 0 {
		fmt.Println(setting.SecretKey)
		if hash == GenerateSignHash(setting.SecretKey, setting.Project.CompanyId, setting.Project.Id) {
			return true, setting.Project.CompanyId, setting.Project.Id
		}

	}

	return false, 0, 0
}
