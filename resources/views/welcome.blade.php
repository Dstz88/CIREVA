<x-public-layout>
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">
        <div
            class="relative rounded-3xl overflow-hidden shadow-2xl min-h-[480px] flex items-center justify-center text-center px-6 py-16 bg-slate-900">
            <!-- Background Image with Overlay -->
            <img src="https://images.unsplash.com/photo-1544644181-1484b3fdfc62?q=80&w=1600&auto=format&fit=crop"
                alt="Cirebon Cultural Heritage"
                class="absolute inset-0 w-full h-full object-cover object-center opacity-30 mix-blend-luminosity">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>

            <!-- Content -->
            <div class="relative z-10 max-w-3xl mx-auto space-y-6">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    Experience the <span class="text-amber-400 font-serif italic">Soul</span> of Cirebon
                </h1>
                <p class="text-base sm:text-lg text-slate-300 font-normal max-w-2xl mx-auto leading-relaxed">
                    Discover, book, and immerse yourself in the rich cultural heritage and vibrant events of the city of
                    guardians.
                </p>

                <!-- Search Pill Bar -->
                <form action="{{ route('events.index') }}" method="GET" class="mt-8 max-w-2xl mx-auto">
                    <div
                        class="flex items-center bg-white/10 backdrop-blur-md border border-white/20 rounded-full p-2 pl-5 shadow-2xl">
                        <svg class="w-5 h-5 text-slate-300 shrink-0 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" placeholder="Search events, categories, or dates..."
                            class="w-full bg-transparent text-white placeholder-slate-400 text-sm focus:outline-none border-none focus:ring-0">
                        <button type="submit"
                            class="bg-amber-400 hover:bg-amber-500 text-slate-950 font-bold px-6 py-2.5 rounded-full text-sm flex items-center gap-2 transition shrink-0 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Explore</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Highlighted Experience Section -->
    @if($featuredevent)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <span class="w-1.5 h-7 bg-amber-600 rounded-full"></span>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">event Pilihan (Rekomendasi)</h2>
            </div>
            <a href="{{ route('events.index') }}"
                class="text-sm font-semibold text-amber-700 hover:text-amber-800 transition">Lihat Semua event +</a>
        </div>

        <!-- Featured Card -->
        <div
            class="bg-[#FCECD5] rounded-3xl p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center border border-amber-200/50 shadow-sm">
            <!-- Left Image -->
            <div class="lg:col-span-6 rounded-2xl overflow-hidden h-72 sm:h-96 shadow-md relative bg-slate-900">
                @if($featuredevent->banner)
                <img src="{{ Storage::url($featuredevent->banner) }}" alt="{{ $featuredevent->title }}"
                    class="w-full h-full object-cover">
                @else
                <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=1000&auto=format&fit=crop"
                    alt="{{ $featuredevent->title }}" class="w-full h-full object-cover">
                @endif
            </div>

            <!-- Right Info -->
            <div class="lg:col-span-6 space-y-5">
                <span
                    class="text-xs font-extrabold tracking-widest text-amber-800 uppercase bg-amber-200/60 px-3 py-1 rounded-full inline-block">
                    {{ $featuredevent->category->name ?? 'REKOMENDASI event' }}
                </span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug">
                    {{ $featuredevent->title }}
                </h3>
                <p class="text-sm sm:text-base text-slate-700 leading-relaxed font-normal line-clamp-3">
                    {{ $featuredevent->description }}
                </p>
                <div class="flex flex-wrap gap-6 text-sm text-slate-800 font-medium pt-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $featuredevent->schedules->first()->start_datetime ?? 'Jadwal Terdekat' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $featuredevent->location->name ?? 'Cirebon' }}</span>
                    </div>
                </div>
                <div class="pt-2">
                    <a href="{{ route('events.show', $featuredevent) }}"
                        class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold px-6 py-3 rounded-xl text-sm transition shadow-md">
                        <span>Lihat Detail & Pesan Tiket</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2 2 2 0 010 4 2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 00-2-2 2 2 0 010-4 2 2 0 002-2V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Published events Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <span class="w-1.5 h-7 bg-amber-600 rounded-full"></span>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">event Kebudayaan Terverifikasi (Siap
                    Tayang)</h2>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('calendar.index') }}"
                    class="text-sm font-semibold text-amber-700 hover:text-amber-800 transition">📅 Kalender Budaya</a>
                <a href="{{ route('events.index') }}"
                    class="text-sm font-semibold text-amber-700 hover:text-amber-800 transition">Semua event +</a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($publishedevents as $ev)
            <div
                class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg transition duration-300 flex flex-col justify-between">
                <div class="h-48 relative bg-slate-900 overflow-hidden">
                    @if($ev->banner)
                    <img src="{{ Storage::url($ev->banner) }}" alt="{{ $ev->title }}"
                        class="w-full h-full object-cover">
                    @else
                    <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop"
                        alt="{{ $ev->title }}" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute top-3 left-3">
                        <span
                            class="bg-white/90 backdrop-blur-md text-slate-900 text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                            {{ $ev->category->name ?? 'Kebudayaan' }}
                        </span>
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <h3 class="text-base font-extrabold text-slate-900 line-clamp-1" title="{{ $ev->title }}">
                            {{ $ev->title }}
                        </h3>
                        <div class="space-y-1 text-[11px] text-slate-500">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $ev->location->name ?? 'Cirebon' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Harga
                                Tiket</span>
                            <span class="text-sm font-extrabold text-amber-700">
                                @php $minP = $ev->tickets->min('price'); @endphp
                                {{ $minP > 0 ? 'Rp ' . number_format($minP, 0, ',', '.') : 'Gratis' }}
                            </span>
                        </div>
                        <a href="{{ route('events.show', $ev) }}"
                            class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">
                            Detail event
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-3xl border border-gray-100">
                Belum ada event terverifikasi yang sedang tayang.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Dynamic Culture Categories Section -->
    @php
    $dbCategories = \App\Models\eventCategory::withCount(['events' => function($q) {
    $q->where('status', 'published');
    }])->get();

    $catImages = [
    'Festival Budaya' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=800&auto=format&fit=crop',
    'Ritual Adat' => 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?q=80&w=800&auto=format&fit=crop',
    'Kesenian' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?q=80&w=800&auto=format&fit=crop',
    ];
    @endphp

    @if($dbCategories->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <span class="w-1.5 h-7 bg-amber-600 rounded-full"></span>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Kategori event Budaya</h2>
            </div>
        </div>

        <!-- Grid of Real DB Categories -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            @foreach($dbCategories as $index => $cat)
            @php
            $colSpan = ($index % 3 == 0) ? 'md:col-span-4' : (($index % 3 == 1) ? 'md:col-span-4' : 'md:col-span-4');
            $img = $catImages[$cat->name] ??
            'https://images.unsplash.com/photo-1606744888344-493238951221?q=80&w=800&auto=format&fit=crop';
            @endphp
            <a href="{{ route('events.index', ['category' => $cat->name]) }}"
                class="{{ $colSpan }} relative rounded-3xl overflow-hidden h-64 group shadow-sm border border-gray-100 block">
                <img src="{{ $img }}" alt="{{ $cat->name }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent p-6 flex flex-col justify-end">
                    <span
                        class="text-[10px] font-extrabold uppercase tracking-widest text-amber-400 bg-slate-900/60 backdrop-blur-md px-3 py-1 rounded-full w-fit mb-2">
                        {{ $cat->events_count }} event Aktif
                    </span>
                    <h3 class="text-xl font-bold text-white">{{ $cat->name }}</h3>
                    <p class="text-xs text-slate-300 mt-1 line-clamp-1">
                        {{ $cat->description ?? 'Ragam acara & pagelaran seni kebudayaan khas Cirebon.' }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</x-public-layout>