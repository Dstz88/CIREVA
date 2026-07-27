<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Enums\SpkStatus;

class EnsureSpkApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user || !$user->organizerProfile) {
            return redirect()->route('organizer.profile.show')->with('warning', 'Profil organizer belum ditemukan.');
        }

        $hasApprovedSpk = $user->organizerProfile->agreements()
            ->where('status', 'approved')
            ->exists();

        if (!$hasApprovedSpk) {
            return redirect()->route('organizer.dashboard')->with('warning', 'Surat Perjanjian Kerjasama (SPK 15%) Anda belum disetujui Admin.');
        }

        return $next($request);
    }
}
