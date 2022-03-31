package utils

import (
"crypto/hmac"
"crypto/sha1"
"crypto/sha256"
"crypto/sha512"
"fmt"
)

func GetSHA1Signature(data []byte) string {
	mac := sha1.New()
	mac.Write(data)
	return fmt.Sprintf("%x", mac.Sum(nil))
}

func GetSHA512SignatureS(data []byte) string {
	mac := sha512.New()
	mac.Write(data)
	return fmt.Sprintf("%x", mac.Sum(nil))
}

func GetSHA256SignatureS(data []byte) string {
	mac := sha256.New()
	mac.Write(data)
	return fmt.Sprintf("%x", mac.Sum(nil))
}

func GetSHA256Signature(secret string, data []byte) string {
	mac := hmac.New(sha256.New, []byte(secret))
	mac.Write(data)
	return fmt.Sprintf("%x", mac.Sum(nil))
}

func GetSHA512Signature(secret string, data []byte) string {
	mac := hmac.New(sha512.New, []byte(secret))
	mac.Write(data)
	return fmt.Sprintf("%x", mac.Sum(nil))
}
