package userRepository

import (
	"gorm.io/gorm"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
	"reflect"
)

func GetUserByEmail(email string, provider string) (user postgres.User) {
	if provider == constants.Individual {
		user = GetWithConditions(map[string]interface{}{"email": email}, func() interface{} { return new(postgres.Individual) })
	} else {
		user = GetWithConditions(map[string]interface{}{"email": email}, func() interface{} { return new(postgres.Member) })
	}
	return
}

func HasUserByEmail(email string, provider string) bool {
	var err error
	var ok bool
	if provider == constants.Individual {
		ok, err = HasWithConditions(map[string]interface{}{"email": email}, func() interface{} { return new(postgres.Individual) })
	} else {
		ok, err = HasWithConditions(map[string]interface{}{"email": email}, func() interface{} { return new(postgres.Member) })
	}

	if err == nil {
		return ok
	}

	return false
}

func GetCorporateById(id uint64, individualId uint64) postgres.User {
	var model *postgres.ApplicantCompany

	query := database.PostgresInstance.Limit(1)
	query.
		Preload("Company").
		Preload("Individual", "id = ?", individualId).
		Where("id = ?", id).
		First(&model)

	if query.Error != nil {
		return nil
	}

	return reflect.ValueOf(model).Interface().(postgres.User)
}

func GetUserById(id uint64, provider string) (user postgres.User) {
	if provider == constants.Individual {
		user = GetWithConditions(map[string]interface{}{"id": id}, func() interface{} { return new(postgres.Individual) })
	} else if provider == constants.Corporate {
		user = GetWithConditions(map[string]interface{}{"id": id}, func() interface{} { return new(postgres.ApplicantCompany) })
	} else {
		user = GetWithConditions(map[string]interface{}{"id": id}, func() interface{} { return new(postgres.Member) })
	}
	return
}

func GetWithConditions(columns map[string]interface{}, mc func() interface{}) postgres.User {
	model := mc()

	query := database.PostgresInstance.Limit(1)

	for column, value := range columns {
		query.Where(column+" = ?", value)
	}

	rModel := reflect.TypeOf(model)

	rec := query.Preload("Company.Project", func(db *gorm.DB) *gorm.DB {
		return db.Order("projects.created_at").Preload("Settings")
	})
	if rModel.Elem().Name() == constants.StructMember {
		rec.Preload("ClientIpAddresses", "client_type = ?", constants.ModelMember)
	} else if rModel.Elem().Name() == constants.StructIndividual {
		rec.
			Preload("ClientIpAddresses", "client_type = ?", constants.ModelIndividual).
			Preload("ApplicantCompany").
			Preload("ApplicantModuleActivity.Module")
	}
	rec.First(model)

	if rec.Error != nil {
		return nil
	}

	return model.(postgres.User)
}

func HasWithConditions(columns map[string]interface{}, mc func() interface{}) (bool, error) {
	model := mc()
	query := database.PostgresInstance.Limit(1)
	for column, value := range columns {
		query.Where(column+" = ?", value)
	}
	exists := query.Find(model)

	ok := false
	if exists.RowsAffected > 0 {
		ok = true
	}

	return ok, exists.Error
}

func SaveUser(user postgres.User) *gorm.DB {
	var model postgres.User

	if user.StructName() == constants.StructMember {
		model = user.(*postgres.Member)
	} else {
		model = user.(*postgres.Individual)
	}

	return database.PostgresInstance.Omit(user.MergeOmit([]string{"Company"})...).Save(model)
}

func UpdatePassword(user postgres.User) *gorm.DB {
	var model postgres.User

	if user.StructName() == constants.StructMember {
		model = user.(*postgres.Member)
	} else {
		model = user.(*postgres.Individual)
	}

	return database.PostgresInstance.Omit(user.MergeOmit([]string{
		"ID", "FirstName", "LastName", "MiddleName", "Email", "Url", "Phone", "CountryId", "CitizenshipCountryId", "State", "City",
		"Address", "Zip", "Nationality", "BirthCountryId", "BirthState", "BirthCity", "BirthAt", "Sex",
		"ProfileAdditionalFields", "PersonalAdditionalFields", "ContactsAdditionalFields", "ApplicantStatusId",
		"IsVerificationPhone", "FullName", "CompanyId", "MemberGroupRoleId", "ApplicantStateReasonId", "ApplicantRiskLevelId",
		"AccountManagerMemberId", "LanguageId", "IsVerificationEmail", "Google2FaSecret", "IsActive", "TwoFactorAuthSettingId",
		"CreatedAt", "UpdatedAt", "BackupCodes", "ClientIpAddresses", "Company", "ApplicantCompany",
	})...).Save(model)

}
