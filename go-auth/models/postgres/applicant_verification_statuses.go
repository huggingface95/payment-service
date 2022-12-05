package postgres

const ApplicantVerificationNotVerifyed = 1
const ApplicantVerificationRequested = 2
const ApplicantVerificationVerifyed = 3

type ApplicantVerificationStatuses struct {
}

func (*ApplicantVerificationStatuses) TableName() string {
	return "applicant_verification_statuses"
}
