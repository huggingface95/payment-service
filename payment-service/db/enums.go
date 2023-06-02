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
	StatusCompleted
	StatusError
	StatusCanceled
	StatusUnsigned
	StatusCreated
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
	case "pending":
		return StatusPending
	case "completed":
		return StatusCompleted
	case "error":
		return StatusError
	case "canceled":
		return StatusCanceled
	case "unsigned":
		return StatusUnsigned
	case "created":
		return StatusCompleted
	}

	return StatusError
}
