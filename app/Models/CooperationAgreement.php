<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\SpkStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CooperationAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_profile_id',
        'agreement_number',
        'version',
        'file_path',
        'signed_at',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'expired_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => SpkStatus::class,
            'signed_at' => 'datetime',
            'approved_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function organizerProfile(): BelongsTo
    {
        return $this->belongsTo(OrganizerProfile::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('status', SpkStatus::Approved);
    }
}
