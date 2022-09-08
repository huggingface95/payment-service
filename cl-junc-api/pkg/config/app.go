package config

type App struct {
	Title        string `json:"title"`
	Url          string `json:"url"`
	PublicUrl    string `json:"public_url"`
	TechEmail    string `json:"tech_email"`
	Mail         Mail   `json:"mail"`
	IsProduction bool   `json:"is_production"`
}
