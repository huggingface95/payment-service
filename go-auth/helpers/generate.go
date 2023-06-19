package helpers

import (
	"crypto/rand"
	"encoding/hex"
	rand2 "math/rand"
)

func GenerateRandomString(length int) string {
	b := make([]byte, length)
	if _, err := rand.Read(b); err != nil {
		return ""
	}
	return hex.EncodeToString(b)
}

func GenerateRandomInteger(low, hi int) int {
	return low + rand2.Intn(hi-low)
}
