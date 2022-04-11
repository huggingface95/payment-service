package main

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/cmd/services"
	"cl-junc-api/pkg/utils/log"
)

func main() {
	log.InitDefault()
	app.Get.Init()
	services.Run()

}
