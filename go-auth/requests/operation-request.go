package requests

type OperationInputs struct {
	OperationName string                 `json:"operationName" binding:"required"`
	Query         string                 `json:"query" binding:"required"`
	Variables     map[string]interface{} `json:"variables"`
}

type OperationHeaders struct {
	Referer  string `header:"pagereferer"`
	TestMode bool   `header:"test-mode"`
}

type LoginHeaders struct {
	Origin string `header:"origin" binding:"required"`
}

type OperationDetails struct {
	Operation string
	Method    string
	Type      string
}
