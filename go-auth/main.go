package main

import (
	"github.com/gin-gonic/gin"
	"jwt-authentication-golang/cache"
	"jwt-authentication-golang/config"
	"jwt-authentication-golang/controllers"
	"jwt-authentication-golang/controllers/individual"
	"jwt-authentication-golang/controllers/member"
	"jwt-authentication-golang/database"
	"jwt-authentication-golang/dto"
	"jwt-authentication-golang/jobs"
	"jwt-authentication-golang/middlewares"
	"jwt-authentication-golang/pkg"
)

func main() {

	pkg.InitDefault()

	config.Conf.Init()
	cache.Caching.Init()
	// Initialize Database
	database.PostgresConnect(config.Conf.Postgres)

	database.ClickhouseConnect(config.Conf.ClickHouse)

	database.RedisConnect(config.Conf.Redis)

	if config.Conf.App.SendEmail {
		pkg.MailConnect(config.Conf.Email)
	}

	dto.DTO.Init()

	go jobs.Init()

	//database.Migrate()

	// Initialize Router
	router := initRouter()
	err := router.Run(":2491")
	if err != nil {
		return
	}
}

func initRouter() *gin.Engine {
	router := gin.Default()

	confirmation := router.Group("/confirmation")
	{
		ip := confirmation.Group("/ip").Use(middlewares.CheckIpConfirmation())
		{
			ip.GET("", controllers.ConfirmationIp)
		}
		email := confirmation.Group("/email").Use(middlewares.CheckIndividualEmailConfirmation())
		{
			email.GET("", controllers.ConfirmationIndividualEmail)
		}

		pass := confirmation.Group("/password")
		{
			pass.POST("", controllers.ChangePassword)
		}
	}

	auth := router.Group("/auth")
	{
		i := auth.Group("applicant")
		{
			i.POST("register", individual.Register)
		}
		auth.POST("reset-password", controllers.ResetPassword)
		auth.POST("login", controllers.Login)
		auth.POST("change", controllers.SelectAccount)
		auth.POST("login-two-factor", controllers.GenerateTwoFactorQr)
		auth.POST("verify-two-factor", controllers.VerifyTwoFactorQr)
		auth.POST("activate-two-factor", controllers.ActivateTwoFactorQr)
		auth.POST("generate-backup-codes", controllers.GenerateBackupCodes)
		auth.POST("store-backup-codes", controllers.StoreBackupCodes)

		m := auth.Group("/").Use(middlewares.AccessAuth())
		{
			m.POST("me", member.Me)
			m.POST("refresh", controllers.Refresh)
			m.POST("disable-two-factor", controllers.DisableTwoFactorQr)
		}
	}

	redirect := router.Group("/api")
	{
		redirect.GET("", controllers.RedirectGetRequest)
		redirect.POST("", controllers.RedirectRequest).Use(middlewares.AccessAuth())
	}
	return router
}
