package logger

import (
	"github.com/rs/zerolog"
	"github.com/rs/zerolog/log"
	"os"
)

func NewLog() zerolog.Logger {

	f, err := os.OpenFile("storage/logger.txt", os.O_RDWR|os.O_CREATE|os.O_APPEND, 0777)

	if err != nil {
		// Can we log an error before we have our logger? :)
		log.Error().Err(err).Msg("there was an error creating a temporary file four our log")
	}

	return zerolog.New(f).With().Logger()
}
