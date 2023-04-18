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

func GetUserById(id uint64, provider string) (user postgres.User) {
	if provider == constants.Individual {
		user = GetWithConditions(map[string]interface{}{"id": id}, func() interface{} { return new(postgres.Individual) })
	} else {
		user = GetWithConditions(map[string]interface{}{"id": id}, func() interface{} { return new(postgres.Member) })
	}
	return
}

func GetWithConditions(columns map[string]interface{}, mc func() interface{}) postgres.User {
	var m string
	model := mc()

	query := database.PostgresInstance.Limit(1)

	for column, value := range columns {
		query.Where(column+" = ?", value)
	}

	rModel := reflect.TypeOf(model)

	if rModel.Elem().Name() == constants.StructMember {
		m = constants.ModelMember
	} else {
		m = constants.ModelIndividual
	}

	rec := query.Preload("ClientIpAddresses", "client_type = ?", m).
		Preload("Company").
		First(model)
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
		"CreatedAt", "UpdatedAt", "BackupCodes", "ClientIpAddresses", "Company",
	})...).Save(model)

}
