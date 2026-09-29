<?php

namespace App\Enums;

// Define possible assessment actions made by Penilai.
enum AssessmentAction: string
{
    case REVIEW = 'REVIEW';
    case REQUEST_REVISION = 'REQUEST_REVISION';
    case APPROVE = 'APPROVE';
    case REJECT = 'REJECT';

    public function label(): string
    {
        return match ($this) {
            self::REVIEW => 'Under Review',
            self::REQUEST_REVISION => 'Request Revision',
            self::APPROVE => 'Approve',
            self::REJECT => 'Reject',
        };
    }
}
