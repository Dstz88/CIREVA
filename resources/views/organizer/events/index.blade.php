<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Kelola event Budaya') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Component -->
                <x-organizer-sidebar />

                <!-- Main Content -->
                <div class="flex-1 space-y-6">
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-6">
                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Daftar event Saya</h3>
                                <p class="text-xs text-slate-500 mt-1">Kelola publikasi, jadwal, dan informasi event
                                    kebudayaan Anda.</p>
                            </div>
                            <a href="{{ route('organizer.events.create') }}"
                                class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Buat event Baru</span>
                            </a>
                        </div>

                        <!-- Success Flash Notification Banner (Tambah / Edit event - Warna Hijau) -->
                        @if(session('success'))
                        <div
                            class="bg-emerald-50 border border-emerald-200 text-emerald-900 p-4 rounded-2xl text-xs font-bold flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-sm shrink-0">
                                    ✓</div>
                                <div>
                                    <div class="font-extrabold text-sm text-emerald-950">Berhasil!</div>
                                    <div class="text-emerald-800 text-xs mt-0.5">{{ session('success') }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Deleted / Info Flash Notification Banner (Hapus event - Warna Merah) -->
                        @if(session('info'))
                        <div
                            class="bg-rose-50 border border-rose-200 text-rose-900 p-4 rounded-2xl text-xs font-bold flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center font-black text-sm shrink-0">
                                    🗑</div>
                                <div>
                                    <div class="font-extrabold text-sm text-rose-950">Informasi Penghapusan event:</div>
                                    <div class="text-rose-800 text-xs mt-0.5">{{ session('info') }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table
                                class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-2xl overflow-hidden">
                                <thead class="bg-slate-900 text-white text-xs">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase tracking-wider">Judul event
                                        </th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase tracking-wider">Tanggal
                                            event</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase tracking-wider">Lokasi</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3.5 text-right font-bold uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100 text-xs text-slate-700">
                                    @forelse($events as $event)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-6 py-4 font-bold text-slate-900">
                                            {{ $event->title }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                            $firstSch = $event->schedules->first();
                                            if ($firstSch && $firstSch->start_datetime) {
                                            $dateStr = \Carbon\Carbon::parse($firstSch->start_datetime)->format('d M
                                            Y');
                                            if ($firstSch->end_datetime && $firstSch->end_datetime !=
                                            $firstSch->start_datetime) {
                                            $dateStr .= ' - ' .
                                            \Carbon\Carbon::parse($firstSch->end_datetime)->format('d M Y');
                                            }
                                            } else {
                                            $dateStr = '-';
                                            }
                                            @endphp
                                            <div class="font-semibold text-slate-900">{{ $dateStr }}</div>
                                            @if($event->schedules->count() > 1)
                                            <span class="text-[10px] text-amber-700 font-bold">({{
                                                $event->schedules->count() }} Jadwal Sesi)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $event->location->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1 inline-flex text-[11px] leading-5 font-bold rounded-full bg-amber-100 text-amber-800">
                                                {{ ucfirst($event->status->value ?? 'Draft') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="{{ route('organizer.events.edit', $event) }}"
                                                class="text-amber-700 font-bold hover:underline">Edit</a>
                                            <form action="{{ route('organizer.events.destroy', $event) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Yakin hapus event ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-rose-600 font-bold hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                            Belum ada event yang dibuat. Klik <span
                                                class="font-bold text-slate-700">Buat event Baru</span> untuk memulai.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-4">
                            {{ $events->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>