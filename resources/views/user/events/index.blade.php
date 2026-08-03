@auth
<x-app-layout>
    <div class="flex min-h-screen bg-slate-50">
        <!-- Sidebar User -->
        <x-user-sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar Search & Profile -->
            <header
                class="bg-white border-b border-slate-100 py-4 px-8 flex items-center justify-end sticky top-0 z-10 shadow-sm">
                <div class="flex items-center gap-4">
                    <!-- Notification Bell -->
                    <x-notification-bell />

                    <div class="flex items-center gap-3 pl-2 border-l border-slate-100">
                        <span class="text-xs font-semibold text-slate-700">Halo, <span
                                class="font-bold text-slate-900">{{ Auth::user()->name }}</span>!</span>
                        <div
                            class="w-9 h-9 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Body Section -->
            <main class="p-8 flex-1 space-y-6">

                <!-- Page Title -->
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Informasi Event</h1>
                    <p class="text-xs text-slate-500 mt-1">Temukan berbagai Event budaya terbaik di Kota Cirebon.</p>
                </div>

                <!-- Top Filter Inputs Box -->
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="{{ route('events.index') }}" method="GET"
                        class="grid grid-cols-1 md:grid-cols-5 gap-3 items-center text-xs">
                        <div class="relative md:col-span-1">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 pl-8 text-xs focus:ring-2 focus:ring-blue-900">
                            <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div>
                            <select name="category"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-xs text-slate-700 focus:ring-2 focus:ring-blue-900">
                                <option value="">Semua Kategori</option>
                                <option value="Festival Budaya" {{ request('category')=='Festival Budaya' ? 'selected'
                                    : '' }}>Festival Budaya</option>
                                <option value="Ritual Adat" {{ request('category')=='Ritual Adat' ? 'selected' : '' }}>
                                    Ritual Adat</option>
                                <option value="Kesenian" {{ request('category')=='Kesenian' ? 'selected' : '' }}>
                                    Kesenian</option>
                            </select>
                        </div>
                        <div>
                            <input type="date" name="date" value="{{ request('date') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-xs text-slate-700 focus:ring-2 focus:ring-blue-900">
                        </div>
                        <div>
                            <select name="location"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-xs text-slate-700 focus:ring-2 focus:ring-blue-900">
                                <option value="">Semua Lokasi</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->name }}" {{ request('location')==$loc->name ? 'selected' : ''
                                    }}>
                                    {{ $loc->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit"
                                class="w-full bg-blue-950 hover:bg-blue-900 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                <span>Filter</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Category Pills & View Switcher Row -->

                <!-- MAIN GRID: event CARDS & RIGHT SIDEBAR -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                    <!-- LEFT event CARDS (3 COLS) -->
                    <div class="lg:col-span-3 space-y-6">
                        <div class="text-xs font-bold text-slate-500">
                            Menampilkan {{ $events->total() }} Event
                        </div>

                        <!-- 4 Columns Cards Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                            @forelse($events as $event)
                            @php
                            $minPrice = $event->tickets ? $event->tickets->min('price') : 75000;
                            @endphp
                            <div
                                class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                <div>
                                    <div class="h-36 relative bg-slate-900">
                                        @if($event->banner)
                                        <img src="{{ Storage::url($event->banner) }}" alt="{{ $event->title }}"
                                            class="w-full h-full object-cover">
                                        @else
                                        <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=400"
                                            alt="event Image" class="w-full h-full object-cover opacity-90">
                                        @endif
                                        <span
                                            class="absolute top-3 left-3 bg-amber-500 text-slate-950 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase">
                                            {{ $event->category->name ?? 'Festival Budaya' }}
                                        </span>
                                    </div>
                                    <div class="p-4 space-y-2">
                                        <h3 class="font-extrabold text-slate-900 text-xs line-clamp-1"
                                            title="{{ $event->title }}">
                                            {{ $event->title }}
                                        </h3>
                                        <div class="space-y-1 text-[11px] text-slate-500">
                                            <p class="flex items-center gap-1.5 truncate">
                                                <span>📍</span>
                                                <span>{{ $event->location->name ?? 'Cirebon' }}</span>
                                            </p>
                                            <p class="flex items-center gap-1.5">
                                                <span>📅</span>
                                                @php
                                                $firstSchedule = $event->schedules->first();
                                                if ($firstSchedule && $firstSchedule->start_datetime) {
                                                $dateText =
                                                \Carbon\Carbon::parse($firstSchedule->start_datetime)->format('d M Y');
                                                if ($firstSchedule->end_datetime && $firstSchedule->end_datetime !=
                                                $firstSchedule->start_datetime) {
                                                $dateText .= ' - ' .
                                                \Carbon\Carbon::parse($firstSchedule->end_datetime)->format('d M Y');
                                                }
                                                } else {
                                                $dateText = \Carbon\Carbon::parse($event->created_at)->format('d M Y');
                                                }
                                                @endphp
                                                <span>{{ $dateText }}</span>
                                            </p>
                                        </div>
                                        <p class="text-xs font-black text-amber-600 pt-1">
                                            {{ $minPrice == 0 ? 'Gratis' : 'Rp ' . number_format($minPrice, 0, ',', '.')
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="p-4 pt-0">
                                    <a href="{{ route('events.show', $event) }}"
                                        class="block w-full text-center bg-slate-50 hover:bg-blue-950 hover:text-white border border-slate-200 text-slate-800 font-bold py-2 rounded-xl text-[11px] transition">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div
                                class="col-span-1 sm:col-span-2 lg:col-span-4 bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm space-y-3">
                                <div
                                    class="w-14 h-14 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto text-2xl">
                                    🎪
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm">Tidak Ada event Ditemukan</h4>
                                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                                    Belum ada event untuk kategori atau kriteria pencarian ini. Silakan coba filter
                                    lainnya.
                                </p>
                            </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        <div class="pt-6 flex justify-center items-center gap-2 text-xs">
                            <button
                                class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-400">&lt;</button>
                            <button
                                class="w-8 h-8 rounded-full bg-blue-950 text-white font-bold flex items-center justify-center">1</button>
                            <button
                                class="w-8 h-8 rounded-full hover:bg-white text-slate-600 font-medium flex items-center justify-center">2</button>
                            <button
                                class="w-8 h-8 rounded-full hover:bg-white text-slate-600 font-medium flex items-center justify-center">3</button>
                            <span class="text-slate-400 px-1">...</span>
                            <button
                                class="w-8 h-8 rounded-full hover:bg-white text-slate-600 font-medium flex items-center justify-center">6</button>
                            <button
                                class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600">&gt;</button>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN (1 COL) -->
                    <div class="space-y-6">

                        <!-- Kategori event Card -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                                <h3 class="font-bold text-slate-900 text-xs">Kategori event</h3>
                                
                            </div>
                            <div class="space-y-2 text-xs">
                                <a href="{{ route('events.index', ['category' => 'Festival Budaya']) }}"
                                    class="flex items-center justify-between py-1 text-slate-700 font-medium hover:text-blue-900 transition">
                                    <span>🎭 Festival Budaya</span>
                                </a>
                                <a href="{{ route('events.index', ['category' => 'Ritual Adat']) }}"
                                    class="flex items-center justify-between py-1 text-slate-700 font-medium hover:text-amber-600 transition">
                                    <span>🌾 Ritual Adat</span>
                                </a>
                                <a href="{{ route('events.index', ['category' => 'Kesenian']) }}"
                                    class="flex items-center justify-between py-1 text-slate-700 font-medium hover:text-emerald-600 transition">
                                    <span>🎨 Kesenian</span>
                                </a>
                            </div>
                        </div>

                        <!-- event Mendatang Card -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                                <h3 class="font-bold text-slate-900 text-xs">Event Mendatang</h3>
                                
                            </div>

                            <div class="space-y-3">
                                @php
                                    $upcomingList = $upcomingEvents ?? \App\Models\Event::with(['location', 'schedules'])
                                        ->where('status', 'published')
                                        ->latest()
                                        ->take(3)
                                        ->get();
                                @endphp

                                @forelse($upcomingList as $upEvent)
                                    @php
                                        $upBanner = asset('image/fotosatu.jpeg');
                                        if ($upEvent->banner) {
                                            $upBanner = \Illuminate\Support\Str::startsWith($upEvent->banner, ['http://', 'https://'])
                                                ? $upEvent->banner
                                                : \Illuminate\Support\Facades\Storage::url($upEvent->banner);
                                        }
                                        $upSched = $upEvent->schedules->first();
                                        $upDate = $upSched?->start_datetime
                                            ? \Carbon\Carbon::parse($upSched->start_datetime)->format('d M Y')
                                            : 'Jadwal Terjadwal';
                                    @endphp
                                    <a href="{{ route('events.show', $upEvent->slug ?? $upEvent->id) }}" class="flex items-center gap-3 group">
                                        <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 font-bold text-xs overflow-hidden">
                                            <img src="{{ $upBanner }}" alt="{{ $upEvent->title }}" class="w-full h-full object-cover group-hover:scale-105 transition"
                                                onerror="this.onerror=null; this.src='{{ asset('image/fotosatu.jpeg') }}';">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-slate-900 text-xs truncate group-hover:text-blue-900 transition">{{ $upEvent->title }}</h4>
                                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $upDate }}</p>
                                            <p class="text-[10px] text-slate-400 truncate">{{ $upEvent->location->name ?? $upEvent->location->city ?? '-' }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-xs text-slate-400 text-center py-2">Belum ada event mendatang.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>

            </main>
        </div>
    </div>
</x-app-layout>
@else
<!-- GUEST PUBLIC LAYOUT (MATCHING IMAGE 1) -->
<x-public-layout>
    <!-- Header Banner -->
    <div class="bg-gradient-to-b from-slate-50 to-white pt-10 pb-6 text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Temukan Event Budaya
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-2xl mx-auto leading-relaxed">
                Jelajahi kekayaan warisan Cirebon melalui pagelaran seni, festival kuliner, dan pameran sejarah yang
                dikurasi khusus untuk Anda.
            </p>
        </div>
    </div>

    <!-- Main Section with Sidebar & Grid -->
    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Left Sidebar Filter -->
                <div class="w-full lg:w-64 shrink-0">
                    <form action="{{ route('events.index') }}" method="GET"
                        class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-6">
                        <div class="flex items-center gap-2 text-slate-800 font-bold text-sm">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Filter Pencarian</span>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">KATEGORI</h4>
                            <div class="space-y-2 text-xs text-slate-700">
                                @php
                                    $selectedCategories = (array) request('category', []);
                                @endphp
                                @if(isset($categories) && count($categories) > 0)
                                    @foreach($categories as $cat)
                                    @php $catName = is_object($cat) ? $cat->name : $cat; @endphp
                                    <label class="flex items-center gap-2 cursor-pointer hover:text-slate-900">
                                        <input type="checkbox" name="category[]" value="{{ $catName }}"
                                            {{ in_array($catName, $selectedCategories) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <span>{{ $catName }}</span>
                                    </label>
                                    @endforeach
                                @else
                                    @foreach(['Seni Tari', 'Kuliner', 'Musik Tradisional', 'Pameran', 'Sejarah'] as $cat)
                                    <label class="flex items-center gap-2 cursor-pointer hover:text-slate-900">
                                        <input type="checkbox" name="category[]" value="{{ $cat }}"
                                            {{ in_array($cat, $selectedCategories) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <span>{{ $cat }}</span>
                                    </label>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#162544] hover:bg-[#0f1b33] text-white font-bold py-3 rounded-full text-xs transition shadow-sm">
                            Terapkan Filter
                        </button>
                    </form>
                </div>

                <!-- Right events Grid Area -->
                <div class="flex-1 space-y-6">

                    <div
                        class="bg-white rounded-2xl p-2 sm:p-3 shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="relative flex-1 w-full flex items-center">
                            <svg class="w-4 h-4 text-slate-400 absolute left-4 pointer-events-none" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari event, lokasi, atau kategori..."
                                class="w-full pl-10 pr-4 py-2 bg-slate-100 border-none rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-amber-500">
                        </div>

                        <form action="{{ route('events.index') }}" method="GET" class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @foreach((array) request('category', []) as $cat)
                                <input type="hidden" name="category[]" value="{{ $cat }}">
                            @endforeach
                            <span class="text-xs text-slate-500 font-medium">Urutkan:</span>
                            <select name="sort" onchange="this.form.submit()"
                                class="bg-slate-100 border-none rounded-xl text-xs font-semibold text-slate-700 py-2 pl-3 pr-8 focus:ring-2 focus:ring-amber-500 cursor-pointer">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($events as $event)
                        @php
                        $lowestPrice = $event->tickets->min('price');
                        @endphp
                        <div
                            class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg transition duration-300 flex flex-col justify-between">
                            <div class="h-44 relative bg-slate-800 overflow-hidden">
                                @php
                                    $bannerUrl = 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop';
                                    if ($event->banner) {
                                        $bannerUrl = Str::startsWith($event->banner, ['http://', 'https://']) 
                                            ? $event->banner 
                                            : Storage::url($event->banner);
                                    }
                                @endphp
                                <img src="{{ $bannerUrl }}" alt="{{ $event->title }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop';">
                                <div class="absolute top-3 left-3">
                                    <span
                                        class="bg-white/90 backdrop-blur-md text-slate-900 text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                                        {{ $event->category->name ?? 'Kebudayaan' }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <h3 class="text-base font-extrabold text-slate-900 line-clamp-1"
                                        title="{{ $event->title }}">
                                        {{ $event->title }}
                                    </h3>
                                    <div class="space-y-1 text-[11px] text-slate-500">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            @php
                                            $firstSched = $event->schedules->first();
                                            $displayDate = $firstSched?->start_datetime ?
                                            \Carbon\Carbon::parse($firstSched->start_datetime)->format('d M Y') :
                                            'Jadwal Terjadwal';
                                            @endphp
                                            <span>{{ $displayDate }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="truncate">{{ $event->location->name ?? $event->location->city
                                                }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-extrabold text-slate-900">
                                            @if(is_null($lowestPrice) || $lowestPrice == 0)
                                            Gratis
                                            @else
                                            Rp {{ number_format($lowestPrice, 0, ',', '.') }}
                                            @endif
                                        </p>
                                    </div>
                                    <a href="{{ route('events.show', $event) }}"
                                        class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-900 text-slate-700 hover:text-white flex items-center justify-center transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-3xl border border-gray-100 shadow-sm space-y-2">
                            <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="font-bold text-slate-700 text-sm">Belum ada Event.</p>
                            <p class="text-xs text-slate-400">Event kebudayaan akan muncul di sini setelah dipublikasikan.</p>
                        </div>
                        @endforelse
                    </div>

                    <div class="pt-6 flex justify-center items-center gap-2 text-xs">
                        <button
                            class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-slate-400">&lt;</button>
                        <button
                            class="w-8 h-8 rounded-full bg-slate-900 text-white font-bold flex items-center justify-center">1</button>
                        <button
                            class="w-8 h-8 rounded-full hover:bg-white text-slate-600 font-medium flex items-center justify-center">2</button>
                        <button
                            class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-slate-600">&gt;</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-public-layout>
@endauth