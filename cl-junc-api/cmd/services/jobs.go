package services

import (
	"cl-junc-api/cmd/services/jobs"
	"time"
)

const JobPeriod = time.Second * 10

func emails() {
	jobs.ProcessEmailQueue()
}

func payments() {
	jobs.ProcessPayQueue()
}

func Jobs() {
	for range time.Tick(JobPeriod) {
		emails()
		payments()
	}
}
