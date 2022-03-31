package config

type App struct {
	Title        string `json:"title"`
	Url          string `json:"url"`
	TechEmail    string `json:"tech_email"`
	IsProduction bool   `json:"is_production"`
}
