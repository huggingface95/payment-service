package main

import (
	"cl-junc-api/cmd/app"
	"cl-junc-api/cmd/services"
)

func main() {
	app.Get.Init()
	services.Run()

}
