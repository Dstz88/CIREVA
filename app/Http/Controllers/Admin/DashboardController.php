<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OrganizerProfile;
use App\Models\CooperationAgreement;
use App\Models\event;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display administrator dashboard metrics & overview.
     */
    public function index(): View
    {
        $metrics = [
            'total_users' => User::whereHas('role', fn($q) => $q->where('name', 'user'))->count(),
            'total_organizers' => User::whereHas('role', fn($q) => $q->where('name', 'organizer'))->count(),
            'pending_organizers' => OrganizerProfile::where('status', 'pending')->count(),
            'pending_spk' => CooperationAgreement::where('status', 'pending')->count(),
            'pending_events' => event::whereIn('status', ['submitted', 'under_review'])->count(),
            'published_events' => event::where('status', 'published')->count(),
            'active_tickets' => Ticket::where('status', 'active')->count(),
            'total_bookings' => Booking::count(),
            'revenue_summary' => Transaction::where('status', 'paid')->orWhere('status', 'success')->sum('amount'),
        ];

        return view('admin.dashboard', compact('metrics'));
    }
}
