package models

type Config struct {
	Url       string `json:"url"`
	Login     string `json:"login"`
	Password  string `json:"password"`
	ResetAuth bool   `json:"reset_auth"`
}
