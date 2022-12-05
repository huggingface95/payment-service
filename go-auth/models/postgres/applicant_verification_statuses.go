package postgres

const NotVerifyed = 1
const Requested = 2
const Verifyed = 3

type ApplicantVerificationStatuses struct {
}

func (*ApplicantVerificationStatuses) TableName() string {
	return "applicant_verification_statuses"
}
