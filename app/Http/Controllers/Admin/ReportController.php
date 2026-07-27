<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use App\Models\event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display admin global sales & platform revenue report with date filters.
     */
    public function index(Request $request): View
    {
        $transactionQuery = Transaction::whereIn('status', ['success', 'paid']);

        if ($request->filled('start_date')) {
            $transactionQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $transactionQuery->whereDate('created_at', '<=', $request->end_date);
        }

        $grossRevenue = (clone $transactionQuery)->sum('amount');
        $platformCommission = $grossRevenue * 0.15; // 15% SPK Fee
        $organizerPayout = $grossRevenue - $platformCommission;

        $totalBookings = Booking::count();
        $totalOrganizers = User::where('role', 'organizer')->count();
        $publishedevents = event::where('status', 'published')->count();

        $transactions = $transactionQuery->with(['booking.user', 'booking.items.ticket.event'])->latest()->paginate(10);

        return view('admin.reports.index', compact(
            'transactions',
            'grossRevenue',
            'platformCommission',
            'organizerPayout',
            'totalBookings',
            'totalOrganizers',
            'publishedevents'
        ));
    }
}
