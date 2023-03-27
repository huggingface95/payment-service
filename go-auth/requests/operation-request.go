package requests

type OperationInputs struct {
	OperationName string `json:"operationName" binding:"required"`
}

type OperationHeaders struct {
	Referer string `header:"referer" binding:"required"`
}
