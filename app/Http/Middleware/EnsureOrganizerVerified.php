<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureOrganizerVerified
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
            return redirect()->route('organizer.profile.show')->with('warning', 'Profil organizer belum ditemukan. Silakan isi data profil terlebih dahulu.');
        }

        $status = $user->organizerProfile->status->value ?? (string) $user->organizerProfile->status;
        if (!in_array(strtolower($status), ['approved', 'verified'])) {
            if ($request->routeIs('organizer.dashboard')) {
                return $next($request);
            }
            return redirect()->route('organizer.dashboard')->with('warning', 'Akun Mitra Organizer Anda masih menunggu verifikasi dari Admin. Fitur pembuatan event dan master data akan aktif setelah diverifikasi.');
        }

        return $next($request);
    }
}
