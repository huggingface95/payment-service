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

	registration := router.Group("/registration")
	{
		registration.POST("private", individual.RegisterPrivate)
		registration.POST("corporate", individual.RegisterCorporate)
	}

	authorization := router.Group("/authorization")
	{
		authorization.POST("test", individual.Authorize)
	}

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
		redirectPost := redirect.Group("").Use(middlewares.AccessAuth())
		{
			redirectPost.POST("", controllers.RedirectRequest)
			redirectPost.POST("/files", controllers.RedirectFilesRequest)
			redirectPost.POST("/email", controllers.RedirectEmailRequest)
			redirectPost.POST("/sms", controllers.RedirectSmsRequest)
			redirectPost.GET("/pdf", controllers.RedirectPdfRequest)
			redirectPost.POST("/generate-2fa-token", controllers.Generate2faToken)
		}
	}
	return router
}
