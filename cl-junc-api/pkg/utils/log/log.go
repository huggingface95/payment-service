package log

import (
	"github.com/rs/zerolog"
	"os"
)

var (
	dl *zerolog.Logger
)

func NewLogger() *zerolog.Logger {

	f, err := os.OpenFile("storage/", os.O_RDWR|os.O_CREATE|os.O_APPEND, 0777)

	if err != nil {
		panic(err)
	}

	logger := zerolog.New(f).With().Logger()
	return &logger
}

func InitDefault() {
	dl = NewLogger()
}

func Debug() *zerolog.Event {
	return dl.Debug()
}

func Info() *zerolog.Event {
	return dl.Info()
}

func Warn() *zerolog.Event {
	return dl.Warn()
}

func Error() *zerolog.Event {
	return dl.Error()
}
