package services

import (
	"cl-junc-api/cmd/services/jobs"
	"time"
)

const JobPeriod = time.Second * 10

func payments() {
	jobs.ProcessPayQueue()
}

func iban() {
	jobs.ProcessIbanIndGenerateQueue()
}

func Jobs() {
	for range time.Tick(JobPeriod) {
		payments()
		iban()
	}
}
