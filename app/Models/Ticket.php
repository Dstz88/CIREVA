<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Builder;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quota',
        'sold',
        'sale_start',
        'sale_end',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
            'sale_start' => 'datetime',
            'sale_end' => 'datetime',
            'price' => 'decimal:2',
            'quota' => 'integer',
            'sold' => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(event::class);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', TicketStatus::Active);
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->where('status', TicketStatus::Active)
            ->whereRaw('quota > sold')
            ->where(function ($q) {
                $q->whereNull('sale_start')->orWhere('sale_start', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('sale_end')->orWhere('sale_end', '>=', now());
            });
    }
}
