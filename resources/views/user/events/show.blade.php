@auth
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $event->title }} - CIREVA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#F8FAFC] text-slate-800">
    <div class="min-h-screen flex">

        <!-- LEFT SIDEBAR FOR USER -->
        <x-user-sidebar />

        <!-- RIGHT MAIN WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- TOP NAVBAR -->
            <header
                class="bg-white border-b border-slate-200 sticky top-0 z-40 px-6 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4 flex-1">
                    <button class="md:hidden text-slate-500 hover:text-slate-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                <!-- Right Top Menu -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('notifications.index') }}"
                        class="relative p-2 text-slate-500 hover:text-slate-700 rounded-full hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            class="absolute top-1 right-1 bg-rose-500 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full">2</span>
                    </a>

                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                        <div
                            class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs uppercase">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                        <span class="text-xs font-semibold text-slate-700 hidden sm:inline-block">Halo, {{
                            Auth::user()->name }}!</span>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT AREA -->
            <main class="p-6 md:p-8 max-w-7xl w-full mx-auto space-y-6">

                <!-- Breadcrumb -->
                <nav class="flex text-xs text-slate-500 gap-2 items-center">
                    <a href="{{ url('/') }}" class="hover:text-slate-900 transition">Beranda</a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('events.index') }}" class="hover:text-slate-900 transition">Informasi event</a>
                    <span>&rsaquo;</span>
                    <span class="font-bold text-slate-800">Detail event</span>
                </nav>

                <!-- HERO CARD -->
                <div
                    class="relative rounded-2xl overflow-hidden shadow-md text-white min-h-[360px] flex flex-col justify-end p-6 md:p-8 bg-slate-900">
                    @if($event->banner)
                    <img src="{{ Storage::url($event->banner) }}" alt="{{ $event->title }}"
                        class="absolute inset-0 w-full h-full object-cover opacity-60">
                    @else
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 opacity-90">
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                    <div class="absolute top-6 left-6 right-6 flex items-center justify-between">
                        <span
                            class="bg-amber-500 text-slate-950 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $event->category->name ?? 'Festival Budaya' }}
                        </span>
                    </div>

                    <div class="relative z-10 space-y-3 max-w-3xl">
                        <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight text-white leading-tight">
                            {{ $event->title }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-slate-200">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $event->location->name ?? 'Cirebon' }}, {{ $event->location->city ?? 'Cirebon'
                                    }}</span>
                            </div>
                            <span>|</span>
                            @php
                            $firstSched = $event->schedules ? $event->schedules->first() : null;
                            if ($firstSched && $firstSched->start_datetime) {
                            $startCarbon = \Carbon\Carbon::parse($firstSched->start_datetime);
                            $endCarbon = $firstSched->end_datetime ? \Carbon\Carbon::parse($firstSched->end_datetime) :
                            null;

                            if ($endCarbon && $endCarbon->format('Y-m-d') !== $startCarbon->format('Y-m-d')) {
                            $startDateFormatted = $startCarbon->format('d M Y') . ' – ' . $endCarbon->format('d M Y');
                            } else {
                            $startDateFormatted = $startCarbon->format('d M Y');
                            }

                            if ($endCarbon && $endCarbon->format('H:i') !== $startCarbon->format('H:i')) {
                            $timeFormatted = $startCarbon->format('H:i') . ' – ' . $endCarbon->format('H:i') . ' WIB';
                            } else {
                            $timeFormatted = $startCarbon->format('H:i') . ' WIB';
                            }
                            } else {
                            $startDateFormatted = \Carbon\Carbon::parse($event->created_at)->format('d M Y');
                            $timeFormatted = '08.00 - 17.00 WIB';
                            }
                            @endphp
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $startDateFormatted }}</span>
                            </div>
                            <span>|</span>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $timeFormatted }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-300 line-clamp-2 leading-relaxed">
                            {{ $event->description }}
                        </p>
                    </div>
                </div>

                <!-- MAIN GRID: LEFT CONTENT & RIGHT SIDEBAR -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- LEFT COLUMN (2 COLS) -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Deskripsi event Box -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-6">
                            <h3 class="font-bold text-slate-900 text-lg">Deskripsi event</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $event->description }}
                            </p>
                        </div>



                        <!-- Lokasi event Box -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-6">
                            <h3 class="font-bold text-slate-900 text-lg">Lokasi event</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                <div
                                    class="h-48 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden relative flex items-center justify-center">
                                    <div class="absolute inset-0 bg-emerald-50/50 flex items-center justify-center">
                                        <div class="text-center p-4">
                                            <div
                                                class="w-10 h-10 bg-rose-500 text-white rounded-full flex items-center justify-center mx-auto shadow-md animate-bounce">
                                                📍
                                            </div>
                                            <span class="font-bold text-xs text-slate-800 mt-2 block">{{
                                                $event->location->name }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4 text-xs">
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm">{{ $event->location->name }}</h4>
                                        <p class="text-slate-500 mt-1 leading-relaxed">{{ $event->location->address }},
                                            {{ $event->location->city ?? 'Kota Cirebon' }}</p>
                                    </div>

                                    <a href="https://maps.google.com/?q={{ urlencode($event->location->name . ' ' . $event->location->address) }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-300 hover:bg-slate-50 rounded-lg font-bold text-slate-700 transition">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        <span>Buka di Google Maps</span>
                                    </a>


                                </div>
                            </div>
                        </div>

                        <!-- Detail Kuota Tiket Box -->
                        @php
                        $hasTickets = $event->tickets && $event->tickets->count() > 0;
                        $totalQuota = $hasTickets ? $event->tickets->sum('quota') : 0;
                        $totalSold = $hasTickets ? $event->tickets->sum('sold') : 0;
                        $totalAvailable = max(0, $totalQuota - $totalSold);
                        $soldPercentage = $totalQuota > 0 ? round(($totalSold / $totalQuota) * 100, 1) : 0;
                        @endphp

                        @if($hasTickets)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-6">
                            <h3 class="font-bold text-slate-900 text-lg">Detail Kuota Tiket</h3>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total
                                        Kuota</span>
                                    <p class="text-xl font-black text-slate-900 mt-1">{{ number_format($totalQuota, 0,
                                        ',', '.') }}</p>
                                    <span class="text-[10px] text-slate-500">Tiket</span>
                                </div>
                                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tiket
                                        Terjual</span>
                                    <p class="text-xl font-black text-slate-900 mt-1">{{ number_format($totalSold, 0,
                                        ',', '.') }}</p>
                                    <span class="text-[10px] text-slate-500">Tiket</span>
                                </div>
                                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tiket
                                        Tersedia</span>
                                    <p class="text-xl font-black text-emerald-600 mt-1">{{
                                        number_format($totalAvailable, 0, ',', '.') }}</p>
                                    <span class="text-[10px] text-slate-500">Tiket</span>
                                </div>
                                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Persentase
                                        Terjual</span>
                                    <p class="text-xl font-black text-slate-900 mt-1">{{ $soldPercentage }}%</p>
                                    <div class="w-full bg-slate-200 h-1.5 rounded-full mt-2 overflow-hidden">
                                        <div class="bg-amber-500 h-full rounded-full"
                                            style="width: {{ $soldPercentage }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3 text-xs text-amber-900">
                                <span class="text-base">💡</span>
                                <div>
                                    <strong class="font-bold block">Catatan</strong>
                                    <p class="mt-0.5">Tiket dapat habis sewaktu-waktu. Segera pesan tiket Anda sebelum
                                        kehabisan!</p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center text-lg font-bold">
                                    🎁
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm">event Terbuka (Gratis)</h3>
                                    <p class="text-xs text-slate-500">event ini gratis dan tidak memerlukan registrasi
                                        tiket terlebih dahulu. Silakan langsung datang ke lokasi event.</p>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>

                    <!-- RIGHT SIDEBAR (1 COL) -->
                    <div class="space-y-6">

                        <!-- Pesan Tiket Box -->
                        @php
                        $hasTickets = $event->tickets && $event->tickets->count() > 0;
                        $minPrice = $hasTickets ? $event->tickets->min('price') : 0;
                        $firstTicket = $hasTickets ? $event->tickets->first() : null;
                        $totalQuota = $hasTickets ? $event->tickets->sum('quota') : 0;
                        $totalSold = $hasTickets ? $event->tickets->sum('sold') : 0;
                        $totalAvailable = max(0, $totalQuota - $totalSold);
                        $soldPercentage = $totalQuota > 0 ? round(($totalSold / $totalQuota) * 100, 1) : 0;
                        $isAvailable = $hasTickets && $totalAvailable > 0;
                        @endphp

                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-5">
                            <div>
                               
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-2xl font-black text-amber-600">
                                        {{ !$hasTickets || $minPrice == 0 ? 'Gratis' : 'Rp ' . number_format($minPrice,
                                        0, ',', '.') }}
                                    </span>
                                    @if($isAvailable)
                                    <span
                                        class="bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                        Tersedia
                                    </span>
                                    @elseif(!$hasTickets)
                                    <span
                                        class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                        Tanpa Tiket
                                    </span>
                                    @else
                                    <span
                                        class="bg-rose-100 text-rose-800 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                        Habis
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                <div class="flex justify-between text-xs text-slate-600 font-medium">
                                    <span>Quota Tiket</span>
                                    <strong>{{ number_format($totalAvailable, 0, ',', '.') }} / {{
                                        number_format($totalQuota, 0, ',', '.') }}</strong>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-amber-500 h-full rounded-full" style="width: {{ $soldPercentage }}%">
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-400">
                                    {{ $hasTickets ? 'Tiket Tersedia' : 'event ini tidak memerlukan pemesanan tiket' }}
                                </p>
                            </div>

                            <div class="space-y-3 pt-2">
                                @if($isAvailable && $firstTicket)
                                <a href="{{ route('user.bookings.create', ['event_id' => $event->id, 'ticket_id' => $firstTicket->id]) }}"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-[#0B1E48] hover:bg-[#071330] text-white font-bold py-3.5 px-4 rounded-xl text-xs transition shadow-md">
                                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2 2 2 0 000 4 2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 00-2-2 2 2 0 000-4 2 2 0 002-2V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                    <span>Pesan Tiket Sekarang</span>
                                </a>
                                @elseif(!$hasTickets)
                                <button disabled
                                    class="w-full inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-400 font-bold py-3.5 px-4 rounded-xl text-xs cursor-not-allowed">
                                    <span>Gratis (Tanpa Tiket)</span>
                                </button>
                                @else
                                <button disabled
                                    class="w-full inline-flex items-center justify-center gap-2 bg-rose-100 text-rose-500 font-bold py-3.5 px-4 rounded-xl text-xs cursor-not-allowed">
                                    <span>Tiket Habis</span>
                                </button>
                                @endif

                                <button
                                    onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan event berhasil disalin!');"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold py-3 px-4 rounded-xl text-xs transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                    <span>Bagikan event</span>
                                </button>
                            </div>
                        </div>

                        <!-- Informasi event Sidebar Box -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                            <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3">Informasi
                                event</h3>

                            <div class="space-y-3.5 text-xs">
                                <div class="flex items-start gap-3">
                                    <span class="text-slate-400 shrink-0 mt-0.5">💎</span>
                                    <div>
                                        <span class="text-slate-400 font-medium">Kategori</span>
                                        <p class="font-bold text-slate-900 mt-0.5">{{ $event->category->name ??
                                            'Festival Budaya' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <span class="text-slate-400 shrink-0 mt-0.5">📍</span>
                                    <div>
                                        <span class="text-slate-400 font-medium">Lokasi</span>
                                        <p class="font-bold text-slate-900 mt-0.5">{{ $event->location->name ??
                                            'Cirebon' }}{{ isset($event->location->city) ? ', ' . $event->location->city
                                            : '' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <span class="text-slate-400 shrink-0 mt-0.5">📅</span>
                                    <div>
                                        <span class="text-slate-400 font-medium">Tanggal</span>
                                        <p class="font-bold text-slate-900 mt-0.5">{{ $startDateFormatted }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <span class="text-slate-400 shrink-0 mt-0.5">⏰</span>
                                    <div>
                                        <span class="text-slate-400 font-medium">Waktu</span>
                                        <p class="font-bold text-slate-900 mt-0.5">{{ $timeFormatted }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <span class="text-slate-400 shrink-0 mt-0.5">👤</span>
                                    <div>
                                        <span class="text-slate-400 font-medium">Penyelenggara</span>
                                        <p class="font-bold text-slate-900 mt-0.5">
                                            {{ $event->organizerProfile->organization_name ??
                                            $event->organizerProfile->owner_name ?? 'Dinas Kebudayaan dan Pariwisata' }}
                                        </p>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!-- event Lainnya Box -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <h3 class="font-bold text-slate-900 text-base">Event Lainnya</h3>
                                <a href="{{ route('events.index') }}"
                                    class="text-[11px] font-bold text-amber-600 hover:underline">Lihat Semua
                                    &rsaquo;</a>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-14 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 font-bold text-xs bg-cover bg-center"
                                        style="background-image: url('https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=150');">
                                        🌃
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs">Cirebon Night Heritage</h4>
                                        <p class="text-[10px] text-slate-500 mt-0.5">5 – 7 Agustus 2026</p>
                                        <p class="text-[10px] text-slate-400">Kawasan Pecinan Cirebon</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-14 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 font-bold text-xs bg-cover bg-center"
                                        style="background-image: url('https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=150');">
                                        🌾
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs">Sedekah Bumi Gunung Jati</h4>
                                        <p class="text-[10px] text-slate-500 mt-0.5">10 September 2026</p>
                                        <p class="text-[10px] text-slate-400">Gunung Jati</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-14 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 font-bold text-xs bg-cover bg-center"
                                        style="background-image: url('https://images.unsplash.com/photo-1606744882647-8a62f8319f6a?w=150');">
                                        🎨
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs">Pameran Batik Cirebon</h4>
                                        <p class="text-[10px] text-slate-500 mt-0.5">1 – 5 Oktober 2026</p>
                                        <p class="text-[10px] text-slate-400">Gedung Kesenian Cirebon</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </main>
        </div>
    </div>
</body>

</html>
@else
<!-- PUBLIC LAYOUT FOR GUEST (NO LEFT SIDEBAR) -->
<x-public-layout>
    <div class="p-6 md:p-8 max-w-7xl w-full mx-auto space-y-6">

        <!-- Breadcrumb -->
        <nav class="flex text-xs text-slate-500 gap-2 items-center">
            <a href="{{ url('/') }}" class="hover:text-slate-900 transition">Beranda</a>
            <span>&rsaquo;</span>
            <a href="{{ route('events.index') }}" class="hover:text-slate-900 transition">Informasi event</a>
            <span>&rsaquo;</span>
            <span class="font-bold text-slate-800">Detail event</span>
        </nav>

        <!-- HERO CARD -->
        <div
            class="relative rounded-2xl overflow-hidden shadow-md text-white min-h-[360px] flex flex-col justify-end p-6 md:p-8 bg-slate-900">
            @if($event->banner)
            <img src="{{ Storage::url($event->banner) }}" alt="{{ $event->title }}"
                class="absolute inset-0 w-full h-full object-cover opacity-60">
            @else
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 opacity-90"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

            <div class="absolute top-6 left-6 right-6 flex items-center justify-between">
                <span
                    class="bg-amber-500 text-slate-950 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                    {{ $event->category->name }}
                </span>
            </div>

            <div class="relative z-10 space-y-3 max-w-3xl">
                <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    {{ $event->title }}
                </h1>
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-slate-200">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $event->location->name }}, {{ $event->location->city ?? 'Cirebon' }}</span>
                    </div>
                    <span>|</span>
                    @php
                    $firstSched = $event->schedules ? $event->schedules->first() : null;
                    $startDateFormatted = $firstSched && $firstSched->start_datetime ?
                    \Carbon\Carbon::parse($firstSched->start_datetime)->format('d – ') .
                    \Carbon\Carbon::parse($firstSched->start_datetime)->addDays(3)->format('d F Y') : '20 – 23 Juli
                    2026';
                    $timeFormatted = $firstSched && $firstSched->start_datetime ?
                    \Carbon\Carbon::parse($firstSched->start_datetime)->format('H.i') . ' – 22.00 WIB' : '09.00 – 22.00
                    WIB';
                    @endphp
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $startDateFormatted }}</span>
                    </div>
                    <span>|</span>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $timeFormatted }}</span>
                    </div>
                </div>
                <p class="text-xs text-slate-300 line-clamp-2 leading-relaxed">
                    {{ $event->description }}
                </p>
            </div>
        </div>

        <!-- MAIN GRID: LEFT CONTENT & RIGHT SIDEBAR -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- LEFT COLUMN (2 COLS) -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Deskripsi event Box -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-6">
                    <h3 class="font-bold text-slate-900 text-lg">Deskripsi event</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $event->description }}
                    </p>





                    <!-- Lokasi event Box -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-6">
                        <h3 class="font-bold text-slate-900 text-lg">Lokasi event</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                            <div
                                class="h-48 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden relative flex items-center justify-center">
                                <div class="absolute inset-0 bg-emerald-50/50 flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <div
                                            class="w-10 h-10 bg-rose-500 text-white rounded-full flex items-center justify-center mx-auto shadow-md animate-bounce">
                                            📍
                                        </div>
                                        <span class="font-bold text-xs text-slate-800 mt-2 block">{{
                                            $event->location->name
                                            }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4 text-xs">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $event->location->name }}</h4>
                                    <p class="text-slate-500 mt-1 leading-relaxed">{{ $event->location->address }}, {{
                                        $event->location->city ?? 'Kota Cirebon' }}</p>
                                </div>

                                <a href="https://maps.google.com/?q={{ urlencode($event->location->name . ' ' . $event->location->address) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-300 hover:bg-slate-50 rounded-lg font-bold text-slate-700 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    <span>Buka di Google Maps</span>
                                </a>


                            </div>
                        </div>
                    </div>

                    <!-- Detail Kuota Tiket Box -->
                    @php
                    $firstTicket = $event->tickets ? $event->tickets->first() : null;
                    $totalQuota = $event->tickets ? $event->tickets->sum('quota') : 1500;
                    $totalSold = $event->tickets ? $event->tickets->sum('sold') : 250;
                    $totalAvailable = max(0, $totalQuota - $totalSold);
                    $soldPercentage = $totalQuota > 0 ? round(($totalSold / $totalQuota) * 100, 1) : 16.7;
                    @endphp
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-6">
                        <h3 class="font-bold text-slate-900 text-lg">Detail Kuota Tiket</h3>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total
                                    Kuota</span>
                                <p class="text-xl font-black text-slate-900 mt-1">{{ number_format($totalQuota, 0, ',',
                                    '.')
                                    }}</p>
                                <span class="text-[10px] text-slate-500">Tiket</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tiket
                                    Terjual</span>
                                <p class="text-xl font-black text-slate-900 mt-1">{{ number_format($totalSold, 0, ',',
                                    '.')
                                    }}</p>
                                <span class="text-[10px] text-slate-500">Tiket</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tiket
                                    Tersedia</span>
                                <p class="text-xl font-black text-emerald-600 mt-1">{{ number_format($totalAvailable, 0,
                                    ',', '.') }}</p>
                                <span class="text-[10px] text-slate-500">Tiket</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Persentase
                                    Terjual</span>
                                <p class="text-xl font-black text-slate-900 mt-1">{{ $soldPercentage }}%</p>
                                <div class="w-full bg-slate-200 h-1.5 rounded-full mt-2 overflow-hidden">
                                    <div class="bg-amber-500 h-full rounded-full" style="width: {{ $soldPercentage }}%">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3 text-xs text-amber-900">
                            <span class="text-base">💡</span>
                            <div>
                                <strong class="font-bold block">Catatan</strong>
                                <p class="mt-0.5">Tiket dapat habis sewaktu-waktu. Segera pesan tiket Anda sebelum
                                    kehabisan!</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT SIDEBAR (1 COL) -->
                <div class="space-y-6">

                    <!-- Pesan Tiket Box -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-5">
                        <div>
                            <span class="text-xs text-slate-400 font-medium">Harga Mulai</span>
                            @php
                            $minPrice = $event->tickets ? $event->tickets->min('price') : 75000;
                            @endphp
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-2xl font-black text-amber-600">
                                    {{ $minPrice == 0 ? 'Gratis' : 'Rp' . number_format($minPrice, 0, ',', '.') }}
                                </span>
                                <span
                                    class="bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                    Tersedia
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <div class="flex justify-between text-xs text-slate-600 font-medium">
                                <span>Quota Tiket</span>
                                <strong>{{ number_format($totalAvailable, 0, ',', '.') }} / {{
                                    number_format($totalQuota, 0,
                                    ',', '.') }}</strong>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-amber-500 h-full rounded-full" style="width: {{ $soldPercentage }}%">
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400">Tiket Tersedia</p>
                        </div>

                        <div class="space-y-3 pt-2">
                            <a href="{{ route('login') }}"
                                class="w-full inline-flex items-center justify-center gap-2 bg-[#0B1E48] hover:bg-[#071330] text-white font-bold py-3.5 px-4 rounded-xl text-xs transition shadow-md">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <span>Masuk untuk Pesan Tiket</span>
                            </a>

                            <button
                                onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan event berhasil disalin!');"
                                class="w-full inline-flex items-center justify-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold py-3 px-4 rounded-xl text-xs transition">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                                <span>Bagikan event</span>
                            </button>
                        </div>
                    </div>

                    <!-- Informasi event Sidebar Box -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                        <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3">Informasi event
                        </h3>

                        <div class="space-y-3.5 text-xs">
                            <div class="flex items-start gap-3">
                                <span class="text-slate-400 shrink-0 mt-0.5">💎</span>
                                <div>
                                    <span class="text-slate-400 font-medium">Kategori</span>
                                    <p class="font-bold text-slate-900 mt-0.5">{{ $event->category->name }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="text-slate-400 shrink-0 mt-0.5">📍</span>
                                <div>
                                    <span class="text-slate-400 font-medium">Lokasi</span>
                                    <p class="font-bold text-slate-900 mt-0.5">{{ $event->location->name }}, {{
                                        $event->location->city ?? 'Cirebon' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="text-slate-400 shrink-0 mt-0.5">📅</span>
                                <div>
                                    <span class="text-slate-400 font-medium">Tanggal</span>
                                    <p class="font-bold text-slate-900 mt-0.5">{{ $startDateFormatted }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="text-slate-400 shrink-0 mt-0.5">⏰</span>
                                <div>
                                    <span class="text-slate-400 font-medium">Waktu</span>
                                    <p class="font-bold text-slate-900 mt-0.5">{{ $timeFormatted }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="text-slate-400 shrink-0 mt-0.5">👤</span>
                                <div>
                                    <span class="text-slate-400 font-medium">Penyelenggara</span>
                                    <p class="font-bold text-slate-900 mt-0.5">{{
                                        $event->organizerProfile->organization_name ?? 'Dinas Kebudayaan dan Pariwisata
                                        Kota
                                        Cirebon' }}</p>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- event Lainnya Box -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="font-bold text-slate-900 text-base">event Lainnya</h3>
                            <a href="{{ route('events.index') }}"
                                class="text-[11px] font-bold text-amber-600 hover:underline">Lihat Semua &rsaquo;</a>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 font-bold text-xs bg-cover bg-center"
                                    style="background-image: url('https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=150');">
                                    🌃
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">Cirebon Night Heritage</h4>
                                    <p class="text-[10px] text-slate-500 mt-0.5">5 – 7 Agustus 2026</p>
                                    <p class="text-[10px] text-slate-400">Kawasan Pecinan Cirebon</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 font-bold text-xs bg-cover bg-center"
                                    style="background-image: url('https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=150');">
                                    🌾
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">Sedekah Bumi Gunung Jati</h4>
                                    <p class="text-[10px] text-slate-500 mt-0.5">10 September 2026</p>
                                    <p class="text-[10px] text-slate-400">Gunung Jati</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 font-bold text-xs bg-cover bg-center"
                                    style="background-image: url('https://images.unsplash.com/photo-1606744882647-8a62f8319f6a?w=150');">
                                    🎨
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">Pameran Batik Cirebon</h4>
                                    <p class="text-[10px] text-slate-500 mt-0.5">1 – 5 Oktober 2026</p>
                                    <p class="text-[10px] text-slate-400">Gedung Kesenian Cirebon</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
</x-public-layout>
@endauth