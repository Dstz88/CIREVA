<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizerProfile;
use App\Enums\OrganizerStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrganizerVerificationController extends Controller
{
    /**
     * Display a listing of organizer verification requests.
     */
    public function index(Request $request): View
    {
        $query = OrganizerProfile::with(['user', 'documents']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $profiles = $query->latest()->paginate(10);

        return view('admin.organizer-verifications.index', compact('profiles'));
    }

    /**
     * Display organizer profile details and documents.
     */
    public function show(OrganizerProfile $organizerVerification): View
    {
        $organizerVerification->load(['user', 'documents', 'agreements']);
        return view('admin.organizer-verifications.show', ['profile' => $organizerVerification]);
    }

    /**
     * Approve organizer profile.
     */
    public function approve(OrganizerProfile $organizerVerification): RedirectResponse
    {
        DB::transaction(function () use ($organizerVerification) {
            $organizerVerification->update([
                'status' => OrganizerStatus::Approved->value ?? 'approved',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'rejection_reason' => null,
            ]);

            // Auto-approve associated SPK signed by organizer
            $organizerVerification->agreements()->update([
                'status' => \App\Enums\SpkStatus::Approved->value,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejected_reason' => null,
            ]);

            // Auto-approve all uploaded documents for this organizer
            $organizerVerification->documents()->update([
                'verification_status' => \App\Enums\DocumentStatus::Approved->value,
            ]);
        });

        return redirect()->route('admin.organizer-verifications.index')
            ->with('success', 'Profil Mitra Organizer, SPK Kerjasama (15%), & Seluruh Dokumen Pendukung berhasil disetujui & diverifikasi!');
    }

    /**
     * Reject organizer profile with reason notes.
     */
    public function reject(Request $request, OrganizerProfile $organizerVerification): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $organizerVerification) {
            $organizerVerification->update([
                'status' => OrganizerStatus::Rejected->value ?? 'rejected',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Auto-reject uploaded documents for this organizer
            $organizerVerification->documents()->update([
                'verification_status' => \App\Enums\DocumentStatus::Rejected->value,
            ]);
        });

        return redirect()->route('admin.organizer-verifications.index')
            ->with('warning', 'Profil Mitra Organizer telah ditolak dengan catatan.');
    }

    /**
     * Delete organizer profile.
     */
    public function destroy(OrganizerProfile $organizerVerification): RedirectResponse
    {
        $name = $organizerVerification->organization_name ?? 'Mitra Organizer';

        $user = $organizerVerification->user;

        DB::transaction(function () use ($organizerVerification, $user) {
            $eventIds = \App\Models\event::withTrashed()->where('organizer_profile_id', $organizerVerification->id)->pluck('id');
            if ($eventIds->isNotEmpty()) {
                $ticketIds = \App\Models\Ticket::withTrashed()->whereIn('event_id', $eventIds)->pluck('id');
                if ($ticketIds->isNotEmpty()) {
                    \App\Models\BookingItem::whereIn('ticket_id', $ticketIds)->delete();
                    \App\Models\Ticket::withTrashed()->whereIn('id', $ticketIds)->forceDelete();
                }
                \App\Models\eventSchedule::whereIn('event_id', $eventIds)->delete();
                \App\Models\event::withTrashed()->whereIn('id', $eventIds)->forceDelete();
            }

            \App\Models\CooperationAgreement::where('organizer_profile_id', $organizerVerification->id)->forceDelete();
            \App\Models\OrganizerDocument::where('organizer_profile_id', $organizerVerification->id)->forceDelete();
            $organizerVerification->forceDelete();

            if ($user) {
                $user->forceDelete();
            }
        });

        return redirect()->route('admin.organizer-verifications.index')
            ->with('success', 'Akun Mitra Organizer "' . $name . '" beserta seluruh profil & datanya telah berhasil dihapus secara permanen.');
    }
}
