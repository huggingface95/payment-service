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

	pkg.MailConnect(config.Conf.Email)

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
	}

	auth := router.Group("/auth")
	{
		i := auth.Group("applicant")
		{
			i.POST("register", individual.Register)
			i.POST("reset-password", individual.ResetPassword)
			iConf := i.Group("confirmation")
			{
				iConfEmail := iConf.Group("email")
				{
					iConfEmail.Use(middlewares.CheckIndividualEmailConfirmation()).GET("", individual.ConfirmationIndividualEmail)
				}
				iConfResetPass := iConf.Group("password")
				{
					iConfResetPass.Use(middlewares.CheckIndividualResetPassword()).POST("change", individual.ChangePassword)
				}
			}
		}

		auth.POST("login", controllers.Login)
		auth.POST("login-two-factor", controllers.GenerateTwoFactorQr)
		auth.POST("verify-two-factor", controllers.VerifyTwoFactorQr)
		auth.POST("activate-two-factor", controllers.ActivateTwoFactorQr)
		auth.POST("generate-backup-codes", controllers.GenerateBackupCodes)
		auth.POST("store-backup-codes", controllers.StoreBackupCodes)

		auth.Group("/").Use(middlewares.Auth())
		{
			auth.POST("me", member.Me)
			auth.POST("refresh", controllers.Refresh)
			auth.POST("disable-two-factor", controllers.DisableTwoFactorQr)
		}
	}

	redirect := router.Group("/api").Use(middlewares.Auth())
	{
		redirect.POST("", controllers.RedirectRequest)
	}
	return router
}
