package services

import (
	"crypto"
	"encoding/base64"
	"fmt"
	"github.com/sec51/twofactor"
	"jwt-authentication-golang/cache"
)

func GenerateTwoFactorQr(clientType string, id uint64, email string, app string, crypt crypto.Hash, digits int) (qr string, secret string, err error) {
	otp, err := twofactor.NewTOTP(email, app, crypt, digits)
	if err != nil {
		return
	}
	code, err := otp.OTP()
	if err != nil {
		return
	}
	fmt.Println(code)
	qrBytes, err := otp.QR()
	if err != nil {
		return
	}

	qr = "data:image/png;base64," + base64.StdEncoding.EncodeToString(qrBytes)
	secret = otp.Secret()

	otpToBytes, err := otp.ToBytes()
	if err != nil {
		return
	}

	cache.Caching.Totp.Set(fmt.Sprintf("%s_%d", clientType, id), otpToBytes)

	return
}

func Validate(id uint64, code string, issuer string, provider string) bool {
	otpBytes, ok := cache.Caching.Totp.Get(fmt.Sprintf("%s_%d", provider, id))
	if ok == false {
		return false
	}
	otp, err := twofactor.TOTPFromBytes(otpBytes, issuer)
	if err != nil {
		return false
	}

	if otp.Validate(code) == nil {
		return true
	}

	return false
}
