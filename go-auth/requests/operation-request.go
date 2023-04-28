package requests

type OperationInputs struct {
	OperationName string `json:"operationName" binding:"required"`
}

type OperationHeaders struct {
	Referer string `header:"pagereferer" binding:"required"`
}

type LoginHeaders struct {
	Origin string `header:"origin" binding:"required"`
}
