<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ScheduleStatus;
use Illuminate\Database\Eloquent\Builder;

class eventSchedule extends Model
{
    protected $fillable = [
        'event_id',
        'start_datetime',
        'end_datetime',
        'timezone',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ScheduleStatus::class,
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(event::class);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', ScheduleStatus::Published);
    }
}
