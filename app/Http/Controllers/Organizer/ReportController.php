<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display organizer sales & revenue summary report with date filters.
     */
    public function index(Request $request): View
    {
        $organizerId = Auth::user()->organizerProfile->id ?? 0;

        $eventQuery = event::where('organizer_profile_id', $organizerId);

        if ($request->filled('category_id')) {
            $eventQuery->where('category_id', $request->category_id);
        }

        $eventIds = $eventQuery->pluck('id');

        $bookingQuery = Booking::whereHas('items.ticket', function ($q) use ($eventIds) {
            $q->whereIn('event_id', $eventIds);
        });

        if ($request->filled('start_date')) {
            $bookingQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $bookingQuery->whereDate('created_at', '<=', $request->end_date);
        }

        $bookings = $bookingQuery->with(['user', 'items.ticket.event', 'transaction'])->latest()->paginate(10);

        $totalBookings = (clone $bookingQuery)->count();
        $grossRevenue = Transaction::whereIn('booking_id', (clone $bookingQuery)->pluck('id'))
            ->whereIn('status', ['success', 'paid'])
            ->sum('amount');

        $platformFee = $grossRevenue * 0.15; // 15% SPK Fee
        $netRevenue = $grossRevenue - $platformFee;

        return view('organizer.reports.index', compact('bookings', 'totalBookings', 'grossRevenue', 'platformFee', 'netRevenue'));
    }
}
