<?php

namespace App\Enums;

enum eventStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case UnderReview = 'under_review';
    case RevisionRequired = 'revision_required';
    case Approved = 'approved';
    case Published = 'published';
    case Ongoing = 'ongoing';
    case Finished = 'finished';
    case Archived = 'archived';
}
