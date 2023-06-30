package db

const (
	AccountStateUnknown AccountStateEnum = iota
	AccountStateWaitingForApproval
	AccountStateWaitingForAccountIbanGeneration
	AccountStateAwaitingAccount
	AccountStateActive
	AccountStateClosed
	AccountStateSuspended
	AccountStateRejected
)

const (
	StatusUnknown StatusEnum = iota
	StatusPending
	StatusSent
	StatusError
	StatusCanceled
	StatusUnsigned
	StatusWaitingExecutionDate
	StatusExecuted
	StatusRefund
)

const (
	OperationTypeIncoming OperationTypeEnum = iota
	OperationTypeOutgoing
	OperationTypeDeposit
)

const (
	EUR CurrencyEnum = iota
	GBP
)

type (
	AccountStateEnum  uint8
	StatusEnum        uint8
	OperationTypeEnum uint8
	CurrencyEnum      uint8
)

func GetStatus(name string) StatusEnum {
	switch name {
	case "Pending":
		return StatusPending
	case "Sent":
		return StatusSent
	case "Error":
		return StatusError
	case "Canceled":
		return StatusCanceled
	case "Unsigned":
		return StatusUnsigned
	case "Waiting execution date":
		return StatusWaitingExecutionDate
	case "Executed":
		return StatusExecuted
	case "Refund":
		return StatusRefund
	default:
		return StatusUnknown
	}
}
