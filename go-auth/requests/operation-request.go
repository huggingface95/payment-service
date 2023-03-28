package requests

type OperationInputs struct {
	OperationName string `json:"operationName" binding:"required"`
}

type OperationHeaders struct {
	Referer string `header:"Referer" binding:"required"`
}
