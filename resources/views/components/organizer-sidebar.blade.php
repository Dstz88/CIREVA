<aside
    class="w-full lg:w-64 bg-slate-900 text-white rounded-3xl p-6 shadow-md border border-slate-800 shrink-0 space-y-6">
    <!-- Brand / Status -->
    <div class="border-b border-slate-800 pb-4">
        <div class="flex items-center gap-2">
            <x-application-logo class="h-7 w-auto text-amber-500 fill-current" />
            <span class="font-extrabold text-lg tracking-wider text-white">MITRA</span>
        </div>
        <p class="text-[11px] text-slate-400 mt-1 truncate">
            {{ Auth::user()->organizerProfile->organization_name ?? Auth::user()->name }}
        </p>
    </div>

    @php
    $profile = Auth::user()?->organizerProfile;
    $status = $profile?->status->value ?? (string) $profile?->status;
    $isVerified = in_array(strtolower((string)$status), ['approved', 'verified']);
    $hasApprovedSpk = $profile?->agreements()->whereIn('status', ['approved', 'signed'])->exists();
    $isLocked = !$isVerified;
    @endphp

    <!-- Navigation Menu -->
    <nav class="space-y-1 text-xs font-semibold">
        <!-- Dashboard -->
        <a href="{{ route('organizer.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('organizer.dashboard') ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Kelola Profil -->
        <a href="{{ route('organizer.profile.show') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('organizer.profile.*') ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Kelola Profil</span>
        </a>

        <!-- Surat Kerjasama (SPK) -->
        <a href="{{ route('organizer.spk.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('organizer.spk.*') ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Surat Kerjasama (SPK)</span>
        </a>

        <!-- Kelola event -->
        @if($isLocked)
            <div class="flex items-center justify-between px-4 py-3 rounded-xl text-slate-500 bg-slate-950/40 cursor-not-allowed border border-slate-800/80" title="Aktif setelah Akun & SPK disetujui Admin">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Kelola event</span>
                </div>
                <svg class="w-3.5 h-3.5 text-amber-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        @else
            <a href="{{ route('organizer.events.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('organizer.events.*') ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Kelola event</span>
            </a>
        @endif

        <!-- Monitoring Pemesanan -->
        @if($isLocked)
            <div class="flex items-center justify-between px-4 py-3 rounded-xl text-slate-500 bg-slate-950/40 cursor-not-allowed border border-slate-800/80" title="Aktif setelah Akun & SPK disetujui Admin">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Monitoring Pemesanan</span>
                </div>
                <svg class="w-3.5 h-3.5 text-amber-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        @else
            <a href="{{ route('organizer.bookings.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('organizer.bookings.*') ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Monitoring Pemesanan</span>
            </a>
        @endif

        <!-- Monitoring Laporan -->
        @if($isLocked)
            <div class="flex items-center justify-between px-4 py-3 rounded-xl text-slate-500 bg-slate-950/40 cursor-not-allowed border border-slate-800/80" title="Aktif setelah Akun & SPK disetujui Admin">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Monitoring Laporan</span>
                </div>
                <svg class="w-3.5 h-3.5 text-amber-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        @else
            <a href="{{ route('organizer.reports.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('organizer.reports.*') ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Monitoring Laporan</span>
            </a>
        @endif
    </nav>

    <!-- Logout -->
    <div class="pt-4 border-t border-slate-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 font-semibold text-xs">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar (Logout)</span>
            </button>
        </form>
    </div>
</aside>