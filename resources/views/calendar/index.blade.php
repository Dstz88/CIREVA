@auth
<x-app-layout>
    <div class="flex min-h-screen bg-slate-50" x-data="{ 
        selectedevent: {
            title: 'Festival Topeng Cirebon',
            category: 'Festival',
            description: 'Seni tari topeng klasik dari Keraton Kasepuhan yang menggambarkan watak manusia.',
            time: '12 Oktober 2024, 19:00 - Selesai',
            location: 'Alun-Alun Sangkala Buana, Cirebon',
            organizer: 'Dinas Kebudayaan Cirebon',
            price: 'Gratis / Open Donation',
            seats: '42/150',
            image: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600'
        },
        showDrawer: false
    }">
        <!-- Sidebar User -->
        <x-user-sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar Search & Profile -->
            <header
                class="bg-white border-b border-slate-100 py-4 px-8 flex items-center justify-end sticky top-0 z-10 shadow-sm">
                <div class="flex items-center gap-4">
                    <x-notification-bell />

                    <div class="flex items-center gap-3 pl-2 border-l border-slate-100">
                        <span class="text-xs font-semibold text-slate-700">Halo, <span
                                class="font-bold text-slate-900">{{ Auth::user()->name }}</span>!</span>
                        <x-user-avatar size="w-9 h-9" textSize="text-xs" />
                    </div>
                </div>
            </header>

            <!-- Main Body Content -->
            <main class="p-8 flex-1 space-y-6">
                <!-- Page Title -->
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kalender Budaya</h1>
                    <p class="text-xs text-slate-500 mt-1">Jadwal lengkap pagelaran seni dan festival budaya Kota
                        Cirebon.</p>
                </div>

                <div class="flex flex-col lg:flex-row gap-6">

                    <!-- Middle Calendar Grid Section -->
                    <div class="flex-1 space-y-6">



                        <!-- Main Calendar Box -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
                            @php
                                $calMonth = isset($cMonth) ? $cMonth : \Carbon\Carbon::now()->startOfMonth();
                                $year = $calMonth->year;
                                $month = $calMonth->month;

                                $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfDay();
                                $endOfMonth = $startOfMonth->copy()->endOfMonth()->endOfDay();

                                // ISO day of week: 1 (Mon) to 7 (Sun)
                                $startDayOfWeek = $startOfMonth->dayOfWeekIso; 
                                $daysInMonth = $startOfMonth->daysInMonth;

                                $prevMonthUrl = route('calendar.index', array_merge(request()->query(), ['month' => $calMonth->copy()->subMonth()->format('Y-m')]));
                                $nextMonthUrl = route('calendar.index', array_merge(request()->query(), ['month' => $calMonth->copy()->addMonth()->format('Y-m')]));

                                // Build event lookup array keyed by date format Y-m-d
                                $eventsByDate = [];
                                foreach ($events as $ev) {
                                    foreach ($ev->schedules as $sched) {
                                        if ($sched->start_datetime) {
                                            $dateKey = \Carbon\Carbon::parse($sched->start_datetime)->format('Y-m-d');
                                            $eventsByDate[$dateKey][] = [
                                                'event' => $ev,
                                                'schedule' => $sched
                                            ];
                                        }
                                    }
                                }
                            @endphp

                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                                <h3 class="text-xl font-extrabold text-slate-900">{{ $calMonth->translatedFormat('F Y') }}</h3>
                                <div class="flex items-center gap-2">
                                    <a href="{{ $prevMonthUrl }}"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition text-xs font-bold flex items-center gap-1">
                                        &larr; Bulan Sebelumnya
                                    </a>
                                    <a href="{{ route('calendar.index') }}"
                                        class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 rounded-xl transition text-xs font-bold">
                                        Bulan Ini
                                    </a>
                                    <a href="{{ $nextMonthUrl }}"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition text-xs font-bold flex items-center gap-1">
                                        Bulan Selanjutnya &rarr;
                                    </a>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table
                                    class="w-full border-collapse border border-indigo-100 rounded-xl overflow-hidden min-w-[600px]">
                                    <thead>
                                        <tr class="bg-[#8198D6] text-white text-xs font-bold text-center">
                                            <th class="py-2 border border-indigo-200/50 w-[14%]">Sen</th>
                                            <th class="py-2 border border-indigo-200/50 w-[14%]">Sel</th>
                                            <th class="py-2 border border-indigo-200/50 w-[14%]">Rab</th>
                                            <th class="py-2 border border-indigo-200/50 w-[14%]">Kam</th>
                                            <th class="py-2 border border-indigo-200/50 w-[14%]">Jum</th>
                                            <th class="py-2 border border-indigo-200/50 w-[14%]">Sab</th>
                                            <th class="py-2 border border-indigo-200/50 w-[14%]">Min</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs text-slate-700">
                                        @php
                                            $dayCounter = 1;
                                            $prevMonthDays = $startOfMonth->copy()->subMonth()->daysInMonth;
                                            $paddingStart = $startDayOfWeek - 1; // Number of days to pad at start
                                            $totalCells = ceil(($daysInMonth + $paddingStart) / 7) * 7;
                                        @endphp

                                        @for ($cell = 0; $cell < $totalCells; $cell++)
                                            @if ($cell % 7 === 0)
                                                <tr class="h-20 align-top">
                                            @endif

                                            @if ($cell < $paddingStart)
                                                {{-- Prev Month Day --}}
                                                @php $prevDayNum = $prevMonthDays - ($paddingStart - $cell - 1); @endphp
                                                <td class="p-1.5 border border-indigo-100 text-slate-300 bg-slate-50/40">
                                                    {{ $prevDayNum }}
                                                </td>
                                            @elseif ($dayCounter <= $daysInMonth)
                                                {{-- Current Month Day --}}
                                                @php
                                                    $cellDateStr = sprintf('%04d-%02d-%02d', $year, $month, $dayCounter);
                                                    $dayEvents = $eventsByDate[$cellDateStr] ?? [];
                                                    $isToday = \Carbon\Carbon::now()->isSameDay(\Carbon\Carbon::createFromDate($year, $month, $dayCounter));
                                                @endphp
                                                <td class="p-1.5 border {{ $isToday ? 'border-2 border-indigo-500 bg-indigo-50/40' : 'border-indigo-100' }} font-bold">
                                                    <span class="{{ $isToday ? 'text-indigo-900 font-extrabold' : '' }}">{{ $dayCounter }}</span>
                                                    @if(!empty($dayEvents))
                                                        <div class="mt-1 space-y-1">
                                                            @foreach($dayEvents as $item)
                                                            @php 
                                                                $evObj = $item['event'];
                                                                $firstTicket = $evObj->tickets->first();
                                                                $minPrice = $evObj->tickets->min('price') ?? 0;
                                                                $timeStr = \Carbon\Carbon::parse($item['schedule']->start_datetime)->format('d M Y, H:i');
                                                            @endphp
                                                            <div @click="selectedevent = {
                                                                    title: '{{ addslashes($evObj->title) }}',
                                                                    category: '{{ addslashes($evObj->category->name ?? 'Budaya') }}',
                                                                    description: '{{ addslashes($evObj->description ?? '') }}',
                                                                    time: '{{ $timeStr }}',
                                                                    location: '{{ addslashes($evObj->location->name ?? 'Cirebon') }}',
                                                                    organizer: '{{ addslashes($evObj->organizerProfile->user->name ?? 'Penyelenggara') }}',
                                                                    price: '{{ $minPrice > 0 ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'Gratis' }}',
                                                                    seats: 'Kuota Tersedia',
                                                                    image: '{{ $evObj->banner ? asset('storage/' . $evObj->banner) : 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600' }}'
                                                                }; showDrawer = true;"
                                                                class="cursor-pointer bg-amber-200 text-amber-900 hover:bg-amber-300 text-[9px] font-bold px-1.5 py-0.5 rounded truncate transition shadow-xs">
                                                                {{ $evObj->title }}
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                @php $dayCounter++; @endphp
                                            @else
                                                {{-- Next Month Day --}}
                                                @php $nextDayNum = $cell - ($paddingStart + $daysInMonth) + 1; @endphp
                                                <td class="p-1.5 border border-indigo-100 text-slate-300 bg-slate-50/40">
                                                    {{ $nextDayNum }}
                                                </td>
                                            @endif

                                            @if (($cell + 1) % 7 === 0)
                                                </tr>
                                            @endif
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                   
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
@else
<!-- GUEST PUBLIC LAYOUT FOR CALENDAR -->
<x-public-layout>
    <div class="py-8 bg-slate-50/50 min-h-screen" x-data="{ 
        selectedevent: {
            title: 'Festival Topeng Cirebon',
            category: 'Festival',
            description: 'Seni tari topeng klasik dari Keraton Kasepuhan yang menggambarkan watak manusia.',
            time: '12 Oktober 2024, 19:00 - Selesai',
            location: 'Alun-Alun Sangkala Buana, Cirebon',
            organizer: 'Dinas Kebudayaan Cirebon',
            price: 'Gratis / Open Donation',
            seats: '42/150',
            image: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600'
        },
        showDrawer: true
    }">
        @php
        $cMonth = isset($currentMonth) ? $currentMonth : now()->startOfMonth();
        $startOfMonth = $cMonth->copy()->startOfMonth();
        $endOfMonth = $cMonth->copy()->endOfMonth();
        $daysInMonth = $cMonth->daysInMonth;

        // ISO-8601 day of week (1 for Monday, 7 for Sunday)
        $startDayOfWeek = $startOfMonth->isoWeekday();
        $prevMonthEnd = $startOfMonth->copy()->subDay();
        $prevMonthDaysToShow = $startDayOfWeek - 1;
        $startPrevDate = $prevMonthEnd->day - $prevMonthDaysToShow + 1;

        $prevMonthUrl = route('calendar.index', array_merge(request()->query(), ['month' =>
        $cMonth->copy()->subMonth()->format('Y-m')]));
        $nextMonthUrl = route('calendar.index', array_merge(request()->query(), ['month' =>
        $cMonth->copy()->addMonth()->format('Y-m')]));
        @endphp

        <div class="py-10 bg-slate-50 min-h-[calc(100vh-5rem)]">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-6">

                    

                    <!-- Middle Calendar Grid -->
                    <div class="flex-1 space-y-6">
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                            <!-- Month Navigation Header -->
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                                <h3 class="text-xl font-extrabold text-slate-900">
                                    {{ $cMonth->translatedFormat('F Y') }}
                                </h3>
                                <div class="flex items-center gap-2">
                                    <a href="{{ $prevMonthUrl }}"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition text-xs font-bold flex items-center gap-1">
                                        &larr; Bulan Sebelumnya
                                    </a>
                                    <a href="{{ route('calendar.index') }}"
                                        class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 rounded-xl transition text-xs font-bold">
                                        Bulan Ini
                                    </a>
                                    <a href="{{ $nextMonthUrl }}"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition text-xs font-bold flex items-center gap-1">
                                        Bulan Selanjutnya &rarr;
                                    </a>
                                </div>
                            </div>

                            <!-- Calendar Grid Table (Original Style Guide) -->
                            <div class="overflow-x-auto">
                                <table
                                    class="w-full border-collapse border border-indigo-100 rounded-xl overflow-hidden min-w-[700px]">
                                    <thead>
                                        <tr class="bg-[#8198D6] text-white text-xs font-bold text-center">
                                            <th class="py-2.5 border border-indigo-200/50 w-[14.28%]">Sen</th>
                                            <th class="py-2.5 border border-indigo-200/50 w-[14.28%]">Sel</th>
                                            <th class="py-2.5 border border-indigo-200/50 w-[14.28%]">Rab</th>
                                            <th class="py-2.5 border border-indigo-200/50 w-[14.28%]">Kam</th>
                                            <th class="py-2.5 border border-indigo-200/50 w-[14.28%]">Jum</th>
                                            <th class="py-2.5 border border-indigo-200/50 w-[14.28%]">Sab</th>
                                            <th class="py-2.5 border border-indigo-200/50 w-[14.28%]">Min</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs text-slate-700">
                                        @php
                                        $currentDayCounter = 1;
                                        $nextMonthCounter = 1;
                                        $totalCells = 35; // 5 weeks x 7 days
                                        if (($prevMonthDaysToShow + $daysInMonth) > 35) {
                                        $totalCells = 42;
                                        }
                                        @endphp

                                        @for($cell = 1; $cell <= $totalCells; $cell++) @if(($cell - 1) % 7==0) <tr
                                            class="h-24 align-top">
                                            @endif

                                            @if($cell <= $prevMonthDaysToShow) <!-- Previous Month Days -->
                                                <td class="p-2 border border-indigo-100 text-slate-400 bg-slate-50/50">
                                                    <span>{{ $startPrevDate + $cell - 1 }}</span>
                                                </td>
                                                @elseif($currentDayCounter <= $daysInMonth) <!-- Current Month Days -->
                                                    @php
                                                    $dateString = $cMonth->format('Y-m-') . sprintf('%02d',
                                                    $currentDayCounter);
                                                    $isToday = $dateString === now()->format('Y-m-d');

                                                    // Find events for this date
                                                    $dayEvents = collect();
                                                    if (isset($events)) {
                                                    foreach($events as $ev) {
                                                    foreach($ev->schedules as $sched) {
                                                    if ($sched->start_datetime &&
                                                    \Carbon\Carbon::parse($sched->start_datetime)->format('Y-m-d') ===
                                                    $dateString) {
                                                    $dayEvents->push($ev);
                                                    break;
                                                    }
                                                    }
                                                    }
                                                    }
                                                    @endphp

                                                    <td
                                                        class="p-2 border border-indigo-100 font-bold {{ $isToday ? 'bg-amber-50/60 ring-2 ring-amber-400 inset-0' : 'bg-white' }}">
                                                        <div class="flex items-center justify-between">
                                                            <span
                                                                class="{{ $isToday ? 'text-amber-700 font-black' : 'text-slate-800' }}">{{
                                                                $currentDayCounter }}</span>
                                                            @if($isToday)
                                                            <span
                                                                class="text-[9px] bg-amber-500 text-white font-extrabold px-1 rounded">Hari
                                                                ini</span>
                                                            @endif
                                                        </div>

                                                        <div class="mt-1.5 space-y-1">
                                                            @foreach($dayEvents->take(2) as $dEv)
                                                            <a href="{{ route('events.show', $dEv) }}"
                                                                title="{{ $dEv->title }}"
                                                                class="block bg-amber-100 hover:bg-amber-200 text-amber-900 text-[10px] font-bold px-1.5 py-1 rounded truncate transition">
                                                                {{ $dEv->title }}
                                                            </a>
                                                            @endforeach
                                                            @if($dayEvents->count() > 2)
                                                            <span
                                                                class="text-[9px] text-slate-500 font-semibold block">+{{
                                                                $dayEvents->count() - 2 }} event lagi</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    @php $currentDayCounter++; @endphp
                                                    @else
                                                    <!-- Next Month Days -->
                                                    <td
                                                        class="p-2 border border-indigo-100 text-slate-400 bg-slate-50/50">
                                                        <span>{{ $nextMonthCounter++ }}</span>
                                                    </td>
                                                    @endif

                                                    @if($cell % 7 == 0)
                                                    </tr>
                                                    @endif
                                                    @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</x-public-layout>
@endauth