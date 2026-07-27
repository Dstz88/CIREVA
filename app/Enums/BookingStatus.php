<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case PaymentCompleted = 'payment_completed';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
}
