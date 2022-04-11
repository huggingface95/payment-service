package utils

import "log"

func IsNilError(err error) bool  {
	if err == nil {
		return true
	}
	log.Println(err)
	return false
}
