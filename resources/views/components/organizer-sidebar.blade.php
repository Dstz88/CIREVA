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
        <a href="{{ route('organizer.events.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('organizer.events.*') ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span>Kelola event</span>
        </a>




        <!-- Monitoring Pemesanan -->
        <a href="{{ route('organizer.bookings.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('organizer.bookings.*') ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>Monitoring Pemesanan</span>
        </a>

        <!-- Monitoring Laporan -->
        <a href="{{ route('organizer.reports.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('organizer.reports.*') ? 'bg-amber-500 text-slate-950 font-bold shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span>Monitoring Laporan</span>
        </a>
    </nav>
</aside>