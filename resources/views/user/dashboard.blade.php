<x-app-layout>
    <div class="flex min-h-screen bg-slate-50">
        <!-- Sidebar -->
        <x-user-sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar Search & Profile -->
            <header
                class="bg-white border-b border-slate-100 py-4 px-8 flex items-center justify-end sticky top-0 z-10 shadow-sm">
                <!-- Right Header Actions -->
                <div class="flex items-center gap-6">
                    <!-- Notification Bell -->
                    <x-notification-bell />

                    <!-- User Profile Top Brief -->
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-600 font-medium">Halo, <strong
                                class="text-slate-900 font-bold">{{ Auth::user()->name }}</strong>!</span>
                        <div class="w-8 h-8 rounded-full bg-[#0096C7] text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm shrink-0 border border-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Main Body -->
            <main class="p-8 space-y-8 flex-1">
                <!-- Top Section: Hero Banner & Profile Box -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                    <!-- Left Column Hero Banner (Slider) -->
                    @php
                    $fallbackSlides = collect([
                        (object)[
                            'title' => 'CIREVA',
                            'subtitle' => 'Temukan dan rasakan pengalaman budaya terbaik di Kota Cirebon.',
                            'banner_url' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=1000&auto=format&fit=crop&q=80'
                        ],
                        (object)[
                            'title' => 'FESTIVAL TARI TOPENG',
                            'subtitle' => 'Pagelaran seni tradisional keraton terbesar tahun 2026.',
                            'banner_url' => 'https://images.unsplash.com/photo-1606744882647-8a62f8319f6a?w=1000&auto=format&fit=crop&q=80'
                        ],
                        (object)[
                            'title' => 'BATIK TRUSMI EXHIBITION',
                            'subtitle' => 'Pameran batik warisan budaya khas Cirebon.',
                            'banner_url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=1000&auto=format&fit=crop&q=80'
                        ]
                    ]);

                    $slides = ($popularevents && $popularevents->count() > 1) ? $popularevents : $fallbackSlides;
                    @endphp

                    <div x-data="{ 
                            activeSlide: 0, 
                            totalSlides: {{ count($slides) }},
                            timer: null,
                            startAutoSlide() {
                                if (this.timer) clearInterval(this.timer);
                                this.timer = setInterval(() => {
                                    this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                                }, 3000);
                            },
                            stopAutoSlide() {
                                if (this.timer) clearInterval(this.timer);
                            }
                         }" x-init="startAutoSlide()" @mouseenter="stopAutoSlide()" @mouseleave="startAutoSlide()"
                        class="lg:col-span-8 bg-slate-950 rounded-3xl text-white relative overflow-hidden flex flex-col justify-between shadow-lg min-h-[220px]">

                        <!-- Slide Background Images -->
                        @foreach($slides as $index => $slide)
                        @php
                        $bannerVal = $slide->banner ?? ($slide->banner_url ?? null);
                        $imgSrc = 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=1000&auto=format&fit=crop&q=80';
                        if ($bannerVal) {
                            $imgSrc = Str::startsWith($bannerVal, ['http://', 'https://']) 
                                ? $bannerVal 
                                : Storage::url($bannerVal);
                        }
                        @endphp
                        <div x-show="activeSlide === {{ $index }}" 
                            x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0 scale-105"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-500"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute inset-0 z-0" x-cloak>
                            <img src="{{ $imgSrc }}" alt="{{ $slide->title ?? 'Event' }}"
                                class="w-full h-full object-cover opacity-50"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=1000&auto=format&fit=crop&q=80';">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-slate-950/20">
                            </div>
                        </div>
                        @endforeach

                        <!-- Decorative Pattern Overlay -->
                        <div
                            class="absolute inset-0 opacity-15 pointer-events-none bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] z-0">
                        </div>

                        <!-- Slide Content -->
                        <div class="relative z-10 p-8 space-y-2 max-w-xl">
                            <span class="text-xs text-slate-300 font-semibold block">Selamat Datang di</span>
                            <h1 class="text-3xl font-black tracking-wider text-white uppercase">CIREVA</h1>
                            <p class="text-[10px] font-extrabold tracking-widest text-amber-400 uppercase">CULTURE &bull; EVENTS &bull; CONNECTIONS</p>
                            <p class="text-xs text-slate-300 pt-1 leading-relaxed">
                                Temukan dan rasakan pengalaman budaya terbaik di Kota Cirebon.
                            </p>
                        </div>

                        <!-- Bottom Controls & Dots Slider Indicator -->
                        <div class="relative z-10 p-8 pt-0 flex items-center justify-between">
                            <a href="{{ route('events.index') }}"
                                class="inline-flex items-center gap-2 bg-[#D4A359] hover:bg-[#c5954c] text-slate-950 font-extrabold text-xs px-6 py-3 rounded-full transition shadow-md">
                                <span>Jelajahi Event</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>

                            <!-- Interactive Dots Slider Indicator -->
                            <div
                                class="flex items-center gap-2 bg-slate-950/50 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10">
                                @foreach($slides as $index => $slide)
                                <button type="button" @click="activeSlide = {{ $index }}; startAutoSlide()"
                                    :class="activeSlide === {{ $index }} ? 'w-5 bg-amber-400 opacity-100' : 'w-2 bg-white opacity-40 hover:opacity-75'"
                                    class="h-2 rounded-full transition-all duration-300 focus:outline-none"
                                    title="Slide {{ $index + 1 }}">
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right Column Profile Widget -->
                    <div
                        class="lg:col-span-4 bg-[#0B1E48] rounded-3xl p-6 text-white flex flex-col justify-between shadow-lg relative overflow-hidden">
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-full bg-cyan-500 overflow-hidden shrink-0 border-2 border-white/20 flex items-center justify-center font-bold text-white text-lg">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="space-y-1">
                                    <h3 class="font-extrabold text-base text-white leading-snug">{{ Auth::user()->name }}</h3>
                                    <p class="text-xs text-slate-300">Pengguna</p>
                                </div>
                            </div>

                            <hr class="border-white/10 my-4">

                            <!-- Stats Counter -->
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div>
                                    <span class="block text-xl font-black text-white">{{ $bookingCount ?? Auth::user()->bookings()->count() }}</span>
                                    <span class="text-[10px] text-slate-300 font-semibold">Pesanan</span>
                                </div>
                                <div>
                                    <span class="block text-xl font-black text-white">{{ $ticketCount ?? 0 }}</span>
                                    <span class="text-[10px] text-slate-300 font-semibold">E-Ticket</span>
                                </div>
                                <div>
                                    <span class="block text-xl font-black text-white">0</span>
                                    <span class="text-[10px] text-slate-300 font-semibold">Wishlist</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Section: event Populer & event Mendatang -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- event Populer (Left 8 Cols) -->
                    <div class="lg:col-span-8 space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-base font-bold text-slate-900">Event Populer Publik</h2>
                            <a href="{{ route('events.index') }}"
                                class="text-xs font-semibold text-blue-900 hover:underline flex items-center gap-1">
                                <span>Lihat Semua</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        <!-- event Cards Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            @forelse($popularevents ?? [] as $ev)
                            <div
                                class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 flex flex-col justify-between">
                                <div class="relative h-32 bg-slate-800">
                                    <img src="{{ $ev->banner ? asset('storage/' . $ev->banner) : 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=500&auto=format&fit=crop&q=60' }}"
                                        alt="{{ $ev->title }}" class="w-full h-full object-cover">
                                    <span
                                        class="absolute top-2 left-2 bg-blue-900 text-white text-[9px] font-bold px-2 py-0.5 rounded-md">{{
                                        $ev->category->name ?? 'event' }}</span>
                                </div>
                                <div class="p-3 space-y-2 flex-1 flex flex-col justify-between">
                                    <div class="space-y-1">
                                        <h3 class="font-bold text-xs text-slate-900 line-clamp-1">{{ $ev->title }}</h3>
                                        <p class="text-[10px] text-slate-500 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            <span class="truncate">{{ $ev->location->name ?? 'Lokasi' }}</span>
                                        </p>
                                        <p class="text-[10px] text-slate-500 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            @php
                                            $firstSched = $ev->schedules->first();
                                            $evDateText = $firstSched && $firstSched->start_datetime
                                            ? \Carbon\Carbon::parse($firstSched->start_datetime)->format('d M Y')
                                            : \Carbon\Carbon::parse($ev->created_at)->format('d M Y');
                                            @endphp
                                            <span>{{ $evDateText }}</span>
                                        </p>
                                    </div>
                                    <div class="pt-2 space-y-2">
                                        @php $minPrice = $ev->tickets->min('price') ?? 0; @endphp
                                        <p class="text-[10px] text-amber-600 font-bold">
                                            {{ $minPrice > 0 ? ' Rp ' . number_format($minPrice, 0, ',', '.') : 'Gratis'
                                            }}
                                        </p>
                                        <a href="{{ route('events.show', $ev) }}"
                                            class="block text-center w-full py-1.5 rounded-lg border border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white text-[10px] font-bold transition">Beli
                                            Tiket</a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div
                                class="col-span-4 p-6 bg-white rounded-2xl border border-slate-100 text-center text-xs text-slate-400">
                                Belum ada event terbit. Silakan cek kembali nanti.
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- event Mendatang List (Right 4 Cols) -->
                    <div class="lg:col-span-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-base font-bold text-slate-900">event Mendatang</h2>
                            <a href="{{ route('events.index') }}"
                                class="text-xs font-semibold text-blue-900 hover:underline flex items-center gap-1">
                                <span>Lihat Semua</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        <div class="space-y-3">
                            @forelse($upcomingevents ?? [] as $uev)
                            <div
                                class="bg-white rounded-2xl p-3 shadow-sm border border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $uev->banner ? asset('storage/' . $uev->banner) : 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=200&auto=format&fit=crop&q=60' }}"
                                        alt="{{ $uev->title }}" class="w-12 h-12 rounded-xl object-cover">
                                    <div>
                                        <h4 class="font-bold text-xs text-slate-900 line-clamp-1">{{ $uev->title }}</h4>
                                        @php
                                        $uSched = $uev->schedules->first();
                                        $uDateText = $uSched && $uSched->start_datetime
                                        ? \Carbon\Carbon::parse($uSched->start_datetime)->format('d M Y')
                                        : \Carbon\Carbon::parse($uev->created_at)->format('d M Y');
                                        @endphp
                                        <p class="text-[10px] text-slate-500">{{ $uDateText }}</p>
                                        <p class="text-[10px] text-slate-400 truncate max-w-[150px]">{{
                                            $uev->location->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('events.show', $uev) }}"
                                    class="text-blue-900 hover:text-blue-950 p-1.5 rounded-lg border border-slate-100 hover:bg-slate-50 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                            @empty
                            <div class="p-4 bg-white rounded-2xl text-center text-xs text-slate-400">
                                Belum ada event mendatang.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: Kategori event -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Kategori event</h2>
                    </div>

                    <!-- 3 Kategori Resmi Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Kategori 1: Festival Budaya -->
                        <a href="{{ route('events.index', ['category' => 'Festival Budaya']) }}"
                            class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col items-center justify-center gap-2 hover:border-blue-900 hover:shadow-md transition group text-center">
                            <div
                                class="w-11 h-11 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Festival Budaya</span>
                        </a>

                        <!-- Kategori 2: Ritual Adat -->
                        <a href="{{ route('events.index', ['category' => 'Ritual Adat']) }}"
                            class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col items-center justify-center gap-2 hover:border-amber-600 hover:shadow-md transition group text-center">
                            <div
                                class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Ritual Adat</span>
                        </a>

                        <!-- Kategori 3: Kesenian -->
                        <a href="{{ route('events.index', ['category' => 'Kesenian']) }}"
                            class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col items-center justify-center gap-2 hover:border-emerald-600 hover:shadow-md transition group text-center">
                            <div
                                class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800">Kesenian</span>
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>