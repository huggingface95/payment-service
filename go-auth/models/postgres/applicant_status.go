package postgres

const ApplicantStatusRequested = 1
const ApplicantStatusDeclined = 2
const ApplicantStatusApproved = 3
const ApplicantStatusPending = 4
const ApplicantStatusDocumentRequested = 5
const ApplicantStatusProcessing = 6
const ApplicantStatusCheckCompleted = 7
const ApplicantStatusVerifyed = 8
const ApplicantStatusRejected = 9
const ApplicantStatusResubmissionRequested = 10
const ApplicantStatusRequiresAction = 11
const ApplicantStatusPrechecked = 12

type ApplicantStatus struct {
}

func (*ApplicantStatus) TableName() string {
	return "applicant_status"
}
