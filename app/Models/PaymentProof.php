<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PaymentProofStatus;
use Illuminate\Database\Eloquent\Builder;

class PaymentProof extends Model
{
    protected $fillable = [
        'transaction_id',
        'file_path',
        'verified_by',
        'verified_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => PaymentProofStatus::class,
            'verified_at' => 'datetime',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePending(Builder $query): void
    {
        $query->where('status', PaymentProofStatus::Pending);
    }
}
