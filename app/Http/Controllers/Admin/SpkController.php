<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperationAgreement;
use App\Enums\SpkStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SpkController extends Controller
{
    /**
     * Display a listing of SPK cooperation agreements for admin review.
     */
    public function index(Request $request): View
    {
        $query = CooperationAgreement::with(['organizerProfile.user', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $agreements = $query->latest()->paginate(10);

        return view('admin.spk.index', compact('agreements'));
    }

    /**
     * Approve SPK cooperation agreement.
     */
    public function approve(CooperationAgreement $agreement): RedirectResponse
    {
        DB::transaction(function () use ($agreement) {
            $agreement->update([
                'status' => SpkStatus::Approved->value,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejected_reason' => null,
            ]);
        });

        return redirect()->route('admin.spk.index')
            ->with('success', 'Surat Perjanjian Kerjasama (SPK) komisi 15% berhasil disetujui!');
    }

    /**
     * Reject SPK cooperation agreement with notes.
     */
    public function reject(Request $request, CooperationAgreement $agreement): RedirectResponse
    {
        $request->validate([
            'rejected_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejected_reason.required' => 'Catatan alasan penolakan SPK wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $agreement) {
            $agreement->update([
                'status' => SpkStatus::Rejected->value,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejected_reason' => $request->rejected_reason,
            ]);
        });

        return redirect()->route('admin.spk.index')
            ->with('warning', 'Surat Perjanjian Kerjasama (SPK) ditolak dengan catatan.');
    }

    /**
     * Delete SPK agreement.
     */
    public function destroy(CooperationAgreement $agreement): RedirectResponse
    {
        $agreementNumber = $agreement->agreement_number ?? 'SPK';
        $agreement->delete();

        return redirect()->route('admin.spk.index')
            ->with('success', 'Dokumen SPK "' . $agreementNumber . '" berhasil dihapus.');
    }
}
