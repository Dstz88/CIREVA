<aside
    class="w-64 bg-white text-slate-700 min-h-screen p-6 border-r border-slate-100 flex flex-col justify-between shrink-0">
    <div class="space-y-8">
        <!-- Logo Brand -->
        <div class="flex items-center gap-3 px-2">
            <x-application-logo class="h-9 w-auto text-blue-900 fill-current" />
            <div>
                <span class="font-black text-xl tracking-wide text-blue-950 block leading-tight">CIREVA</span>
                <span class="text-[9px] font-semibold text-amber-600 tracking-wider uppercase block">CULTURE • eventS •
                    CONNECTIONS</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-1.5 text-xs font-medium">
            <!-- Dashboard -->
            <a href="{{ route('user.dashboard') }}"
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition font-semibold {{ request()->routeIs('user.dashboard') ? 'bg-blue-950 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Informasi event -->
            <a href="{{ route('events.index') }}"
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition {{ request()->routeIs('events.*') ? 'bg-blue-950 text-white shadow-md font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Informasi event</span>
            </a>

            <!-- Kalender Budaya -->
            <a href="{{ route('calendar.index') }}"
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition {{ request()->routeIs('calendar.*') ? 'bg-blue-950 text-white shadow-md font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Kalender Budaya</span>
            </a>

            <!-- Pesan Tiket -->
            <a href="{{ route('user.bookings.index') }}"
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.bookings.*') ? 'bg-blue-950 text-white shadow-md font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Pesan Tiket</span>
            </a>

            <!-- Profil Saya -->
            <a href="{{ route('user.profile.edit') }}"
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.profile.*') ? 'bg-blue-950 text-white shadow-md font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Profil Saya</span>
            </a>



            <!-- E-Ticket Saya -->
            <a href="{{ route('user.tickets.index') }}"
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.tickets.*') ? 'bg-blue-950 text-white shadow-md font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span>E-Ticket Saya</span>
            </a>
        </nav>
    </div>

    <!-- Logout -->
    <div class="pt-6 border-t border-slate-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl transition text-red-500 hover:bg-red-50 font-semibold text-xs">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>