<?php

namespace App\Enums;

enum ScheduleStatus: string
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Published = 'published';
    case Ongoing = 'ongoing';
    case Finished = 'finished';
    case Cancelled = 'cancelled';
}
