<?php

namespace App\Enums;

enum ApplicantStatusEnum: int
{
    case REQUESTED = 1;
    case DECLINED = 2;
    case APPROVED = 3;
    case PENDING = 4;
    case DOCUMENT_REQUESTED = 5;
    case PROCESSING = 6;
    case CHECK_COMPLETED = 7;
    case VERIFIED = 8;
    case REJECTED = 9;
    case RESUBMISSION_REQUESTED = 10;
    case REQUIRES_ACTION = 11;
    case PRECHECKED = 12;

    public function toString(): string
    {
        return match ($this) {
            self::REQUESTED => 'Requested',
            self::DECLINED => 'Declined',
            self::APPROVED => 'Approved',
            self::DOCUMENT_REQUESTED => 'Document Requested',
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::CHECK_COMPLETED => 'Check Completed',
            self::VERIFIED => 'Verified',
            self::REJECTED => 'Rejected',
            self::RESUBMISSION_REQUESTED => 'Resubmission Requested',
            self::REQUIRES_ACTION => 'Requires Action',
            self::PRECHECKED => 'Prechecked',
        };
    }
}
