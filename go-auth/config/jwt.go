package config

import (
	"os"
	"strconv"
)

var JwtConf = JwtConfig{}

type JwtConfig struct {
	Algorithm            string
	PayloadMemberPrv     string
	PayloadIndividualPrv string
	PayloadUrl           string
	Ttl                  int
	BlockAccountTtl      int
	MfaAttempts          int
}

func (j *JwtConfig) Load() *JwtConfig {
	ttl, err := strconv.Atoi(os.Getenv("JWT_TTL"))
	if err != nil {
		panic(err)
	}
	blockAccountTtl, err := strconv.Atoi(os.Getenv("JWT_BLOCK_ACCOUNT_TTL"))
	if err != nil {
		panic(err)
	}
	mfaAttempts, err := strconv.Atoi(os.Getenv("JWT_MFA_ATTEMPTS"))
	if err != nil {
		panic(err)
	}

	j.Ttl = ttl
	j.BlockAccountTtl = blockAccountTtl
	j.MfaAttempts = mfaAttempts
	j.Algorithm = os.Getenv("JWT_ALGORITHM")
	j.PayloadMemberPrv = os.Getenv("JWT_PAYLOAD_MEMBER_PRV")
	j.PayloadIndividualPrv = os.Getenv("JWT_PAYLOAD_INDIVIDUAL_PRV")
	j.PayloadUrl = os.Getenv("JWT_PAYLOAD_URL")

	return j
}
