package services

func Run() {
	go Jobs()
	Web()
}
