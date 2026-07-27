<?php

namespace App\Enums;

enum SpkStatus: string
{
    case Draft = 'draft';
    case Generated = 'generated';
    case PendingSignature = 'pending_signature';
    case Signed = 'signed';
    case UnderReview = 'under_review';
    case RevisionRequired = 'revision_required';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Expired = 'expired';
}
