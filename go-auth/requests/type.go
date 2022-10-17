package requests

type HeaderProviderRequest struct {
	ProviderType string `header:"PROVIDER_TYPE" binding:"required"`
}
