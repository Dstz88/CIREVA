<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Builder;

class OrganizerDocument extends Model
{
    protected $fillable = [
        'organizer_profile_id',
        'document_type',
        'file_path',
        'verification_status',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'verification_status' => DocumentStatus::class,
            'verified_at' => 'datetime',
        ];
    }

    public function organizerProfile(): BelongsTo
    {
        return $this->belongsTo(OrganizerProfile::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('verification_status', DocumentStatus::Approved);
    }

    public function scopePending(Builder $query): void
    {
        $query->where('verification_status', DocumentStatus::Pending);
    }
}
