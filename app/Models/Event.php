<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\eventStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class event extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'organizer_profile_id',
        'category_id',
        'location_id',
        'title',
        'slug',
        'description',
        'banner',
        'status',
        'approved_by',
        'approved_at',
        'published_by',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => eventStatus::class,
            'approved_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function organizerProfile(): BelongsTo
    {
        return $this->belongsTo(OrganizerProfile::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(eventCategory::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(eventLocation::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(eventSchedule::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', eventStatus::Published);
    }

    public function scopeDraft(Builder $query): void
    {
        $query->where('status', eventStatus::Draft);
    }

    public function scopeOngoing(Builder $query): void
    {
        $query->where('status', eventStatus::Ongoing);
    }
}
