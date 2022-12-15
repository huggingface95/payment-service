<?php

namespace App\Enums;

enum KycTimelineActionTypeEnum: string
{
    case DOCUMENT_UPLOAD = 'document_upload';
    case DOCUMENT_STATE = 'document_state';
    case VERIFICATION = 'verification';
    case EMAIL = 'email';
    case PROFILE = 'profile';
}
