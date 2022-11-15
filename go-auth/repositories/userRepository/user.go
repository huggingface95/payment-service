package userRepository

import (
	"gorm.io/gorm"
	"jwt-authentication-golang/constants"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/models/postgres"
	"jwt-authentication-golang/repositories/oauthRepository"
	"reflect"
)

func GetUserByEmail(email string, provider string) (user postgres.User) {
	oauthClient := oauthRepository.GetOauthClientByType(provider, constants.Personal)
	if provider == constants.Individual {
		user = GetWithConditions(map[string]interface{}{"email": email}, oauthClient.Id, func() interface{} { return new(postgres.Individual) })
	} else {
		user = GetWithConditions(map[string]interface{}{"email": email}, oauthClient.Id, func() interface{} { return new(postgres.Member) })
	}
	return
}

func GetUserById(id uint64, provider string) (user postgres.User) {
	oauthClient := oauthRepository.GetOauthClientByType(provider, constants.Personal)
	if provider == constants.Individual {
		user = GetWithConditions(map[string]interface{}{"id": id}, oauthClient.Id, func() interface{} { return new(postgres.Individual) })
	} else {
		user = GetWithConditions(map[string]interface{}{"id": id}, oauthClient.Id, func() interface{} { return new(postgres.Member) })
	}
	return
}

func GetWithConditions(columns map[string]interface{}, oauthClientId uint64, mc func() interface{}) postgres.User {
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

	query.Preload("ClientIpAddresses", "client_type = ?", m).
		Preload("OauthAccessTokens", "client_id = ?", oauthClientId).
		Preload("Company").
		First(model)
	return model.(postgres.User)
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

func HasUserByEmail(email string, provider string) bool {
	if GetUserByEmail(email, provider) == nil {
		return false
	}
	return true
}
