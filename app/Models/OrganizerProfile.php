<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\OrganizerStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizerProfile extends Model
{
    use SoftDeletes, HasFactory;

    protected static function booted(): void
    {
        static::deleting(function (OrganizerProfile $profile) {
            $profile->agreements()->forceDelete();
            $profile->documents()->forceDelete();
        });
    }

    protected $fillable = [
        'user_id',
        'organization_name',
        'owner_name',
        'phone',
        'address',
        'description',
        'logo',
        'status',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrganizerStatus::class,
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OrganizerDocument::class);
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(CooperationAgreement::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(event::class);
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('status', OrganizerStatus::Approved);
    }

    public function scopePending(Builder $query): void
    {
        $query->where('status', OrganizerStatus::Pending);
    }
}
