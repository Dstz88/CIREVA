<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'booking_code',
        'total_amount',
        'status',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => BookingStatus::class,
            'total_amount' => 'decimal:2',
            'service_fee' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'paid_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function scopePending(Builder $query): void
    {
        $query->where('status', BookingStatus::Pending);
    }

    public function scopeConfirmed(Builder $query): void
    {
        $query->where('status', BookingStatus::Confirmed);
    }
}
