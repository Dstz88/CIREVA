<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\event;
use App\Enums\eventStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class eventVerificationController extends Controller
{
    /**
     * Display a listing of submitted events for admin moderation.
     */
    public function index(Request $request): View
    {
        // Auto-hide/exclude events created more than 7 days ago
        $query = event::with(['organizerProfile.user', 'location', 'category'])
            ->where('created_at', '>=', now()->subDays(7));

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->latest()->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Approve and publish submitted event.
     */
    public function approve(event $event): RedirectResponse
    {
        DB::transaction(function () use ($event) {
            $event->update([
                'status' => eventStatus::Published->value,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);
        });

        return redirect()->route('admin.events.index')
            ->with('success', 'event "' . $event->title . '" berhasil dipublikasikan dan masuk ke Kalender Budaya!');
    }

    /**
     * Reject or request revision for event with notes.
     */
    public function reject(Request $request, event $event): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejection_reason.required' => 'Catatan revisi/penolakan event wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $event) {
            $event->update([
                'status' => eventStatus::RevisionRequired->value,
            ]);
        });

        return redirect()->route('admin.events.index')
            ->with('warning', 'Pengajuan event telah ditolak/minta revisi dengan catatan.');
    }

    /**
     * Delete submitted event by admin.
     */
    public function destroy(event $event): RedirectResponse
    {
        $title = $event->title;
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'event "' . $title . '" berhasil dihapus dari sistem!');
    }
}
