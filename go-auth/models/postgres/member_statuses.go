package postgres

const MemberStatusActive = 1
const MemberStatusInactive = 2
const MemberStatusSuspended = 3

type MemberStatus struct {
}

func (*MemberStatus) TableName() string {
	return "member_statuses"
}
