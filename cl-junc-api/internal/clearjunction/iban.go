package clearjunction

import (
	"cl-junc-api/internal/clearjunction/models"
)

func (cj *ClearJunction) CreateIban(request models.IbanCreateRequest) (result *models.IbanCreateResponse, err error) {
	err = cj.post(request, &result, "gate", "iban/individualCreateV2")
	return
}

func (cj *ClearJunction) GetIbanStatus(orderReference string) (result *models.IbanStatusResponse, err error) {
	err = cj.get(&result, "gate", "iban/status/orderReference/"+orderReference)
	return
}

func (cj *ClearJunction) GetIbanCompanyStatus(clientCustomerId string) (result *models.IbanStatusWithCustomerIdResponse, err error) {
	err = cj.get(&result, "gate", "allocate/v2/list/iban/"+clientCustomerId)
	return
}
