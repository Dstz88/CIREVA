<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\CooperationAgreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SpkController extends Controller
{
    /**
     * Display the Organizer's Surat Perjanjian Kerjasama (SPK).
     */
    public function index(): View
    {
        $profile = Auth::user()->organizerProfile;
        
        $agreement = CooperationAgreement::where('organizer_profile_id', $profile->id ?? 0)->latest()->first();

        if (!$agreement && $profile) {
            $agreement = CooperationAgreement::create([
                'organizer_profile_id' => $profile->id,
                'agreement_number' => 'SPK-' . str_pad($profile->id, 5, '0', STR_PAD_LEFT) . '-15PCT',
                'version' => 'v1.0',
                'file_path' => 'spk/agreements/SPK-' . str_pad($profile->id, 5, '0', STR_PAD_LEFT) . '.pdf',
                'signed_at' => now(),
                'status' => 'signed',
            ]);
        }

        return view('organizer.spk.index', compact('agreement', 'profile'));
    }

    /**
     * Export printable PDF / HTML layout for SPK document.
     */
    public function exportPdf()
    {
        $profile = Auth::user()->organizerProfile;
        $agreement = CooperationAgreement::where('organizer_profile_id', $profile->id ?? 0)->latest()->first();

        return view('organizer.spk.pdf', compact('agreement', 'profile'));
    }

    /**
     * Digitally sign the SPK agreement.
     */
    public function sign(Request $request)
    {
        $profile = Auth::user()->organizerProfile;
        if (!$profile) {
            return back()->withErrors(['error' => 'Profil organizer tidak ditemukan.']);
        }

        $agreement = CooperationAgreement::where('organizer_profile_id', $profile->id)->latest()->first();
        if (!$agreement) {
            return back()->withErrors(['error' => 'Dokumen SPK tidak ditemukan.']);
        }

        $agreement->update([
            'signed_at' => now(),
            'status' => \App\Enums\SpkStatus::Signed->value,
        ]);

        return back()->with('success', 'Dokumen SPK berhasil ditandatangani secara digital!');
    }
}
