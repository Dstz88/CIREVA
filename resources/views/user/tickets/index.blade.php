<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <div class="flex min-h-screen bg-slate-50" x-data="{ 
        showTicketModal: false, 
        activeTicket: null, 
        search: '', 
        currentTab: 'upcoming',
        sortBy: 'newest'
    }">
        <!-- Sidebar User -->
        <x-user-sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar Search & Profile -->
            <header
                class="bg-white border-b border-slate-100 py-4 px-4 sm:px-8 flex items-center justify-end sticky top-0 z-10 shadow-sm">
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="{{ route('notifications.index') }}"
                        class="relative p-2 text-slate-500 hover:text-slate-700 rounded-full hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            class="absolute top-1 right-1 bg-rose-500 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full">2</span>
                    </a>

                    <div class="flex items-center gap-3 pl-2 border-l border-slate-100">
                        <span class="text-xs font-semibold text-slate-700 hidden md:inline">Halo, <span
                                class="font-bold text-slate-900">{{ Auth::user()->name }}</span>!</span>
                        <div
                            class="w-9 h-9 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Body Content -->
            <main class="p-4 sm:p-8 flex-1 space-y-6 flex flex-col justify-between">
                <div class="space-y-6">

                    <!-- Header Row: Title & Right Stat Cards -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="max-w-2xl">
                            <h1 class="text-2xl sm:text-3xl font-black text-[#0B1E48] tracking-tight">Tiket Saya</h1>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Kelola dan lihat tiket event budaya Anda. Nikmati kemudahan akses digital untuk
                                pengalaman tradisi yang tak terlupakan.
                            </p>
                        </div>

                        <!-- Right Stat Cards -->
                        <div class="flex items-center gap-3 shrink-0 overflow-x-auto pb-1 sm:pb-0">
                            <div
                                class="bg-[#F1F5F9] rounded-2xl px-5 py-3 text-center border border-slate-200/60 shadow-sm min-w-[100px]">
                                <span class="block text-xl font-black text-slate-900">{{ count($tickets) > 0 ?
                                    count($tickets) : 2 }}</span>
                                <span
                                    class="text-[9px] font-extrabold tracking-wider text-slate-500 uppercase block mt-0.5">AKTIF</span>
                            </div>

                            <div
                                class="bg-[#F1F5F9] rounded-2xl px-5 py-3 text-center border border-slate-200/60 shadow-sm min-w-[100px]">
                                <span class="block text-xl font-black text-slate-900">0</span>
                                <span
                                    class="text-[9px] font-extrabold tracking-wider text-slate-500 uppercase block mt-0.5">SELESAI</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Cards Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @php
                        $userConfirmedBookings = isset($userBookings) ? $userBookings : [];
                        @endphp

                        @forelse($userConfirmedBookings as $b)
                        @foreach($b->items as $item)
                        @php
                        $event = $item->ticket?->event ?? null;
                        $title = $event->title ?? $item->ticket?->name ?? 'Tiket Event Budaya';
                        $loc = $event->location->name ?? 'Keraton Kasepuhan, Cirebon';
                        $tCode = $b->booking_code;
                        @endphp
                        <div
                            class="bg-white rounded-3xl border border-slate-200/80 p-4 shadow-sm flex flex-col sm:flex-row gap-4 hover:shadow-md transition duration-300">

                            <!-- Left Image Banner Column -->
                            <div
                                class="w-full sm:w-44 h-48 sm:h-auto rounded-2xl overflow-hidden relative shrink-0 bg-slate-900">
                                @if($event && $event->banner)
                                <img src="{{ Storage::url($event->banner) }}" alt="{{ $title }}"
                                    class="w-full h-full object-cover">
                                @else
                                <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=400"
                                    alt="Event Banner" class="w-full h-full object-cover">
                                @endif

                                <div class="absolute top-3 left-3">
                                    <span
                                        class="bg-[#D4A359] text-white text-[9px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm">
                                        {{ $event->category->name ?? 'FESTIVAL BUDAYA' }}
                                    </span>
                                </div>

                                <div class="absolute bottom-3 left-3">
                                    <span
                                        class="bg-white/90 backdrop-blur-md text-slate-900 text-[10px] font-extrabold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Aktif &bull; Lunas</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Right Details Column -->
                            <div class="flex-1 flex flex-col justify-between space-y-3">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-black text-slate-900 text-base leading-snug line-clamp-2">
                                            {{ $title }}
                                        </h3>
                                        <span class="text-xs font-mono font-bold text-slate-500 shrink-0">
                                            {{ $tCode }}
                                        </span>
                                    </div>

                                    <div class="space-y-1.5 text-xs text-slate-600 mt-2.5">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#D4A359] shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $b->created_at->format('d M Y') }} &bull; {{ $item->quantity }}x {{ $item->ticket?->name ?? 'Tiket' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#D4A359] shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="truncate">{{ $loc }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-b border-dashed border-slate-200"></div>

                                <div class="flex items-center gap-2 pt-1">
                                    <button type="button"
                                        @click="activeTicket = {{ json_encode(['id' => $b->id, 'title' => $title, 'code' => $tCode, 'location' => $loc, 'name' => $item->ticket?->name]) }}; showTicketModal = true"
                                        class="flex-1 bg-[#D4A359] hover:bg-[#C59B4E] text-slate-950 font-extrabold py-2.5 px-4 rounded-xl text-xs transition shadow-sm text-center">
                                        Lihat QR E-Ticket
                                    </button>

                                    <a href="{{ route('user.bookings.show', $b) }}"
                                        class="p-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-xl transition font-bold text-xs"
                                        title="Detail Pemesanan">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @empty
                        @php
                        $mockTickets = [
                        ['title' => 'Festival Tari Topeng Cirebon', 'cat' => 'SENI TARI', 'code' => 'CRV-982144', 'date'
                        => '15 Agustus 2026 • 19:00 WIB', 'loc' => 'Keraton Kasepuhan, Cirebon', 'badge' => 'Aktif • Lunas',
                        'img' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=400'],
                        ['title' => 'Pameran Batik Trusmi 2026', 'cat' => 'PAMERAN', 'code' => 'CRV-441299', 'date' =>
                        '20 Sep 2026 • 10:00 WIB', 'loc' => 'Desa Trusmi, Cirebon', 'badge' => 'Aktif • Lunas',
                        'img' => 'https://images.unsplash.com/photo-1606744882647-8a62f8319f6a?w=400']
                        ];
                        @endphp
                        @foreach($mockTickets as $mt)
                        <div x-show="!search || '{{ strtolower($mt['title']) }}'.includes(search.toLowerCase()) || '{{ strtolower($mt['loc']) }}'.includes(search.toLowerCase())"
                            class="bg-white rounded-3xl border border-slate-200/80 p-4 shadow-sm flex flex-col sm:flex-row gap-4 hover:shadow-md transition duration-300">
                            <div
                                class="w-full sm:w-44 h-48 sm:h-auto rounded-2xl overflow-hidden relative shrink-0 bg-slate-900">
                                <img src="{{ $mt['img'] }}" alt="{{ $mt['title'] }}" class="w-full h-full object-cover">
                                <div class="absolute top-3 left-3">
                                    <span
                                        class="bg-[#D4A359] text-white text-[9px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm">
                                        {{ $mt['cat'] }}
                                    </span>
                                </div>
                                <div class="absolute bottom-3 left-3">
                                    <span
                                        class="bg-white/90 backdrop-blur-md text-slate-900 text-[10px] font-extrabold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>{{ $mt['badge'] }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1 flex flex-col justify-between space-y-3">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-black text-slate-900 text-base leading-snug line-clamp-2">
                                            {{ $mt['title'] }}
                                        </h3>
                                        <span class="text-xs font-mono font-bold text-slate-500 shrink-0">
                                            {{ $mt['code'] }}
                                        </span>
                                    </div>
                                    <div class="space-y-1.5 text-xs text-slate-600 mt-2.5">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#D4A359] shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $mt['date'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#D4A359] shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="truncate">{{ $mt['loc'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-b border-dashed border-slate-200"></div>
                                <div class="flex items-center gap-2 pt-1">
                                    <button type="button"
                                        @click="activeTicket = {{ json_encode(['id' => 1, 'title' => $mt['title'], 'code' => $mt['code'], 'location' => $mt['loc'], 'name' => 'Tiket Reguler']) }}; showTicketModal = true"
                                        class="flex-1 bg-[#D4A359] hover:bg-[#C59B4E] text-slate-950 font-extrabold py-2.5 px-4 rounded-xl text-xs transition shadow-sm text-center">
                                        Lihat QR E-Ticket
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endforelse
                    </div>

                    <!-- Empty state for Completed and Cancelled tabs -->
                    <div x-show="currentTab !== 'upcoming'" x-cloak
                        class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center space-y-3">
                        <div
                            class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-slate-800 text-sm">Tidak ada tiket di kategori ini</h4>
                        <p class="text-xs text-slate-500">Tiket event Anda di kategori ini akan muncul otomatis di sini.
                        </p>
                    </div>

                </div>

                <!-- Footer Section matching screenshot bottom -->
                <footer class="bg-[#0B1E48] text-white rounded-3xl p-8 mt-12 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Brand column -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('images/LOGO CIREVA.jpeg') }}" alt="CIREVA Logo"
                                    class="h-10 w-auto object-contain rounded-xl shadow-sm">
                                <span class="font-black text-xl tracking-wider text-white">CIREVA</span>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed max-w-sm">
                                Connecting the world to the hidden cultural treasures of Cirebon. Experience
                                authenticity, heritage, and modern convenience in one gateway.
                            </p>
                        </div>

                        <!-- Navigation column -->
                        <div class="space-y-3">
                            <h4 class="font-extrabold text-xs tracking-wider uppercase text-amber-400">NAVIGATION</h4>
                            <ul class="space-y-2 text-xs text-slate-300">
                                <li><a href="#" class="hover:text-white transition">Cultural Mission</a></li>
                                <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
                                <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                            </ul>
                        </div>

                        <!-- Legal column -->
                        <div class="space-y-3">
                            <h4 class="font-extrabold text-xs tracking-wider uppercase text-amber-400">LEGAL</h4>
                            <ul class="space-y-2 text-xs text-slate-300">
                                <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                                <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>

                    <div
                        class="pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
                        <p>&copy; 2024 CIREVA Premium Heritage Gateway. All rights reserved.</p>
                        <div class="flex items-center gap-3">
                            <span
                                class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 hover:text-white transition cursor-pointer">🌐</span>
                            <span
                                class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 hover:text-white transition cursor-pointer">✉️</span>
                        </div>
                    </div>
                </footer>

            </main>

            <!-- E-Ticket Preview Modal (Promax QR Code View) -->
            <div x-show="showTicketModal"
                class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4"
                x-cloak>
                <div class="bg-white rounded-3xl p-6 max-w-sm w-full space-y-5 shadow-2xl text-center relative overflow-hidden"
                    @click.outside="showTicketModal = false">

                    <!-- Header -->
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <div class="text-left">
                            <h4 class="font-black text-slate-900 text-base">E-Tiket Digital</h4>
                            <p class="text-[10px] text-slate-400">CIREVA Heritage Gateway</p>
                        </div>
                        <button type="button" @click="showTicketModal = false"
                            class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 font-bold flex items-center justify-center text-lg transition">&times;</button>
                    </div>

                    <!-- Ticket Info Card -->
                    <div class="bg-gradient-to-br from-[#0A142F] via-[#0E1A3D] to-[#0A142F] text-white rounded-2xl p-5 space-y-3 shadow-inner">
                        <div class="space-y-1">
                            <span class="inline-block bg-[#D4A359]/20 text-[#D4A359] border border-[#D4A359]/40 text-[10px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider"
                                x-text="activeTicket?.code || '#CRV-982144'"></span>
                            <h3 class="font-black text-sm text-white mt-1 leading-tight"
                                x-text="activeTicket?.title || 'Festival Tari Topeng Cirebon'"></h3>
                            <p class="text-[11px] text-slate-300 flex items-center justify-center gap-1"
                                x-text="activeTicket?.location || 'Keraton Kasepuhan, Cirebon'"></p>
                        </div>

                        <!-- QR Code Container -->
                        <div class="bg-white p-4 rounded-2xl inline-block shadow-lg my-1">
                            <svg class="w-40 h-40 text-slate-950 mx-auto" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M2,2H10V10H2V2M4,4V8H8V4H4M11,2H13V4H11V2M14,2H22V10H14V2M16,4V8H20V4H16M2,14H10V22H2V14M4,16V20H8V16H4M19,19V22H22V19H19M11,14H13V16H11V14M14,14H16V16H14V14M17,14H19V16H17V14M19,11H22V13H19V11M14,17H16V19H14V17M11,19H13V22H11V19M16,19H18V22H16V19Z" />
                            </svg>
                            <span class="block text-[11px] font-mono font-extrabold text-slate-900 tracking-wider mt-1.5" x-text="activeTicket?.code"></span>
                        </div>

                        <p class="text-[10px] font-bold text-amber-400 tracking-widest uppercase flex items-center justify-center gap-1">
                            ⚡ TUNJUKKAN QR CODE INI SAAT GATE ENTRY
                        </p>
                    </div>

                    <!-- Footer Action -->
                    <button type="button" @click="showTicketModal = false"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-extrabold py-3 rounded-xl text-xs transition shadow-md">
                        Tutup Modal
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>