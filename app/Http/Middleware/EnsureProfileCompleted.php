<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if ($request->routeIs('organizer.profile.*')) {
            return $next($request);
        }

        if (!$user || !$user->organizerProfile) {
            return redirect()->route('organizer.profile.show')->with('warning', 'Lengkapi profil organizer Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
