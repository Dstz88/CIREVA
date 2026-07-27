<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'transaction_number',
        'payment_method',
        'amount',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => TransactionStatus::class,
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function paymentProof(): HasOne
    {
        return $this->hasOne(PaymentProof::class);
    }

    public function scopePending(Builder $query): void
    {
        $query->where('status', TransactionStatus::Pending);
    }

    public function scopePaid(Builder $query): void
    {
        $query->where('status', TransactionStatus::Success);
    }
}
