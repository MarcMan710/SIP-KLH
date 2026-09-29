<?php

namespace App\Enums;

// Define all possible states of a document application.
//
// Recommended workflow:
//
// DRAFT
//     ↓
// SUBMITTED
//     ↓
// UNDER_REVIEW
//     ├── REVISION_REQUIRED
//     │       ↓
//     │   RESUBMITTED
//     │       ↓
//     │   UNDER_REVIEW
//     │
//     ├── APPROVED
//     │
//     └── REJECTED
//
// Centralize status values so that controllers and services
// do not use inconsistent status strings.
//
// Status transitions should be controlled by the
// ProjectService and assessment workflow.
enum ProjectStatus: string
{
    case DRAFT = 'DRAFT';
    case SUBMITTED = 'SUBMITTED';
    case UNDER_REVIEW = 'UNDER_REVIEW';
    case REVISION_REQUIRED = 'REVISION_REQUIRED';
    case RESUBMITTED = 'RESUBMITTED';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SUBMITTED => 'Submitted',
            self::UNDER_REVIEW => 'Under Review',
            self::REVISION_REQUIRED => 'Revision Required',
            self::RESUBMITTED => 'Resubmitted',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }
}
