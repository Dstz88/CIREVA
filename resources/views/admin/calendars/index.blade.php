<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">

            <!-- Admin Sidebar Component -->
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <main class="flex-1 space-y-6">
                <!-- Header -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Kalender Budaya (Admin Monitoring)</h3>
                        <p class="text-xs text-slate-500 mt-1">Pemantauan jadwal pelaksanaan event kebudayaan Cirebon.
                        </p>
                    </div>

                    <!-- Auto Deletion Notice Alert -->
                    <div
                        class="bg-amber-50 text-amber-900 border border-amber-200 p-4 rounded-2xl text-xs flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <strong class="font-extrabold">Informasi Penghapusan Otomatis:</strong> event yang dibuat
                            lebih dari <strong>7 hari</strong> lalu secara otomatis dihapus & disembunyikan oleh sistem
                            dari halaman admin dan penonton.
                        </div>
                    </div>
                </div>

                <!-- Tab Section for Upcoming vs Past events -->
                <div x-data="{ tab: 'upcoming' }"
                    class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div class="flex gap-3">
                            <button @click="tab = 'upcoming'"
                                :class="tab === 'upcoming' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-5 py-2.5 rounded-full text-xs transition">
                                📅 Event Akan Datang ({{ $upcomingevents->count() }})
                            </button>
                            <button @click="tab = 'past'"
                                :class="tab === 'past' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-5 py-2.5 rounded-full text-xs transition">
                                ⌛ Event Sudah Berlalu ({{ $pastevents->count() }})
                            </button>
                        </div>
                    </div>

                    <!-- Upcoming events Table -->
                    <div x-show="tab === 'upcoming'" class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-2xl overflow-hidden">
                            <thead class="bg-slate-900 text-white text-xs">
                                <tr>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Nama event</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Mitra Penyelenggara</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Lokasi</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Jadwal Pelaksanaan</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 text-xs text-slate-700">
                                @forelse($upcomingevents as $ev)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $ev->title }}</div>
                                        <div class="text-[11px] text-amber-700 font-semibold">{{ $ev->category->name ??
                                            '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{
                                            $ev->organizerProfile->organization_name ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $ev->organizerProfile->user->email ??
                                            '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $ev->location->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @forelse($ev->schedules as $sch)
                                        <div class="font-bold text-slate-900">{{ $sch->start_datetime?->format('d M Y,
                                            H:i') }} - {{ $sch->end_datetime?->format('H:i') }}</div>
                                        @empty
                                        <div class="text-slate-400">-</div>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 inline-flex text-[11px] leading-5 font-bold rounded-full bg-emerald-100 text-emerald-800">
                                            AKAN DATANG
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                        Tidak ada jadwal event mendatang.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Past events Table -->
                    <div x-show="tab === 'past'" class="overflow-x-auto" x-cloak>
                        <table
                            class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-2xl overflow-hidden">
                            <thead class="bg-slate-900 text-white text-xs">
                                <tr>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Nama event</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Mitra Penyelenggara</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Lokasi</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Jadwal Selesai</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 text-xs text-slate-700">
                                @forelse($pastevents as $ev)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $ev->title }}</div>
                                        <div class="text-[11px] text-amber-700 font-semibold">{{ $ev->category->name ??
                                            '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{
                                            $ev->organizerProfile->organization_name ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $ev->organizerProfile->user->email ??
                                            '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $ev->location->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @forelse($ev->schedules as $sch)
                                        <div class="font-bold text-slate-900">{{ $sch->end_datetime?->format('d M Y,
                                            H:i') }}</div>
                                        @empty
                                        <div class="text-slate-400">-</div>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 inline-flex text-[11px] leading-5 font-bold rounded-full bg-slate-100 text-slate-600">
                                            SELESAI / BERLALU
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                        Tidak ada riwayat event yang telah berlalu.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

        </div>
    </div>
</x-app-layout>