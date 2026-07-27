<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Moderasi & Verifikasi event Budaya') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Admin Sidebar -->
                <x-admin-sidebar />

                <!-- Main Content -->
                <div class="flex-1 space-y-6">
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-6">

                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Verifikasi Pengajuan event</h3>
                                <p class="text-xs text-slate-500 mt-1">Moderasi dan setujui publikasi event budaya yang
                                    diajukan oleh Mitra Organizer.</p>
                            </div>

                            <form method="GET" action="{{ route('admin.events.index') }}"
                                class="flex items-center gap-2">
                                <select name="status" onchange="this.form.submit()"
                                    class="text-xs border-gray-300 rounded-xl focus:border-amber-500 focus:ring-amber-500">
                                    <option value="">Semua Status</option>
                                    <option value="submitted" {{ request('status')==='submitted' ? 'selected' : '' }}>
                                        Submitted (Pengajuan)</option>
                                    <option value="published" {{ request('status')==='published' ? 'selected' : '' }}>
                                        Published (Tayang)</option>
                                </select>
                            </form>
                        </div>

                        <!-- Auto Deletion Notice -->
                        <div
                            class="bg-amber-50 text-amber-900 border border-amber-200 p-4 rounded-2xl text-xs flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <strong class="font-extrabold">Informasi Penghapusan Otomatis:</strong> event yang
                                dibuat lebih dari <strong>7 hari</strong> lalu secara otomatis dihapus & disembunyikan
                                oleh sistem dari halaman admin dan penonton.
                            </div>
                        </div>

                        <!-- Flash Messages -->
                        @if(session('success'))
                        <div
                            class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-xs font-bold border border-emerald-200">
                            {{ session('success') }}
                        </div>
                        @endif
                        @if(session('warning'))
                        <div
                            class="bg-amber-50 text-amber-800 p-4 rounded-2xl text-xs font-bold border border-amber-200">
                            {{ session('warning') }}
                        </div>
                        @endif

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table
                                class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-2xl overflow-hidden">
                                <thead class="bg-slate-900 text-white text-xs">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Judul event</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Penyelenggara (Mitra)</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Lokasi</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Status</th>
                                        <th class="px-6 py-3.5 text-right font-bold uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100 text-xs text-slate-700">
                                    @forelse($events as $event)
                                    <tr class="hover:bg-slate-50/80 transition" x-data="{ showDetailModal: false }">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900">{{ $event->title }}</div>
                                            <div class="text-[11px] text-amber-700 font-semibold">{{
                                                $event->category->name ?? 'Kebudayaan' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900">{{
                                                $event->organizerProfile->organization_name ?? '-' }}</div>
                                            <div class="text-[11px] text-slate-400">{{
                                                $event->organizerProfile->user->email ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $event->location->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 inline-flex text-[11px] leading-5 font-bold rounded-full 
                                                    @if($event->status->value === 'published') bg-emerald-100 text-emerald-800
                                                    @elseif($event->status->value === 'submitted' || $event->status->value === 'under_review') bg-amber-100 text-amber-800
                                                    @elseif($event->status->value === 'revision_required') bg-rose-100 text-rose-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                {{ strtoupper($event->status->value) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <!-- Detail Button -->
                                            <button type="button" @click="showDetailModal = true"
                                                class="bg-slate-100 hover:bg-slate-200 text-slate-800 px-3 py-1.5 rounded-xl font-bold transition inline-block text-xs">
                                                🔍 Detail
                                            </button>

                                            @if($event->status->value !== 'published')
                                            <!-- Approve / Publish Form -->
                                            <form action="{{ route('admin.events.approve', $event) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    onclick="return confirm('Setujui dan publikasikan event ini?')"
                                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-xl font-bold transition inline-block text-xs">
                                                    Publikasikan
                                                </button>
                                            </form>
                                            @else
                                            <span class="text-emerald-700 font-bold text-xs">✓ Publik</span>
                                            @endif


                                            <!-- Detail event Modal -->
                                            <div x-show="showDetailModal"
                                                class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4 text-left"
                                                x-cloak>
                                                <div
                                                    class="bg-white rounded-3xl p-6 max-w-xl w-full space-y-4 shadow-xl max-h-[90vh] overflow-y-auto">
                                                    <div
                                                        class="flex justify-between items-center border-b border-gray-100 pb-3">
                                                        <h4 class="font-extrabold text-slate-900 text-base">Rincian
                                                            Detail Pengajuan event</h4>
                                                        <button @click="showDetailModal = false"
                                                            class="text-gray-400 hover:text-gray-600 font-bold">✕</button>
                                                    </div>

                                                    <!-- Banner Image -->
                                                    @if($event->banner)
                                                    <div class="h-48 rounded-2xl overflow-hidden bg-slate-900">
                                                        <img src="{{ Storage::url($event->banner) }}"
                                                            alt="{{ $event->title }}"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    @endif

                                                    <div class="space-y-3 text-xs text-slate-700">
                                                        <div>
                                                            <span
                                                                class="font-bold text-slate-400 uppercase text-[10px]">Judul
                                                                event</span>
                                                            <div class="font-bold text-slate-900 text-sm mt-0.5">{{
                                                                $event->title }}</div>
                                                        </div>

                                                        <div
                                                            class="grid grid-cols-2 gap-4 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                                                            <div>
                                                                <span
                                                                    class="font-bold text-slate-400 uppercase text-[10px]">Kategori</span>
                                                                <div class="font-bold text-amber-700 mt-0.5">{{
                                                                    $event->category->name ?? '-' }}</div>
                                                            </div>
                                                            <div>
                                                                <span
                                                                    class="font-bold text-slate-400 uppercase text-[10px]">Penyelenggara
                                                                    (Mitra)</span>
                                                                <div class="font-bold text-slate-900 mt-0.5">{{
                                                                    $event->organizerProfile->organization_name ?? '-'
                                                                    }}</div>
                                                            </div>
                                                            <div>
                                                                <span
                                                                    class="font-bold text-slate-400 uppercase text-[10px]">Lokasi
                                                                    Pelaksanaan</span>
                                                                <div class="font-bold text-slate-900 mt-0.5">{{
                                                                    $event->location->name ?? '-' }}</div>
                                                            </div>
                                                            <div>
                                                                <span
                                                                    class="font-bold text-slate-400 uppercase text-[10px]">Status
                                                                    Pengajuan</span>
                                                                <div class="font-bold text-emerald-600 mt-0.5">{{
                                                                    strtoupper($event->status->value) }}</div>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="bg-amber-50/60 p-3 rounded-2xl border border-amber-100/80 space-y-2">
                                                            <span
                                                                class="font-bold text-amber-900 uppercase text-[10px] tracking-wider block">📅
                                                                Tanggal & Jadwal Pelaksanaan</span>
                                                            @forelse($event->schedules as $sch)
                                                            <div
                                                                class="flex items-center justify-between text-xs bg-white p-2.5 rounded-xl border border-amber-100">
                                                                <div class="font-bold text-slate-900">
                                                                    {{
                                                                    \Carbon\Carbon::parse($sch->start_datetime)->format('d
                                                                    M Y, H:i') }} WIB
                                                                    @if($sch->end_datetime)
                                                                    - {{
                                                                    \Carbon\Carbon::parse($sch->end_datetime)->format('d
                                                                    M Y, H:i') }} WIB
                                                                    @endif
                                                                </div>
                                                                <span
                                                                    class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800">
                                                                    {{ ucfirst($sch->status->value ?? 'Scheduled') }}
                                                                </span>
                                                            </div>
                                                            @empty
                                                            <p class="text-[11px] text-slate-400 italic">Belum ada
                                                                rincian jadwal yang ditambahkan.</p>
                                                            @endforelse
                                                        </div>

                                                        <div>
                                                            <span
                                                                class="font-bold text-slate-400 uppercase text-[10px]">Deskripsi
                                                                & Rundown</span>
                                                            <div
                                                                class="mt-1 p-3 bg-white border border-gray-200 rounded-2xl whitespace-pre-line text-slate-700">
                                                                {{ $event->description }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex justify-end pt-3 border-t border-gray-100">
                                                        <button type="button" @click="showDetailModal = false"
                                                            class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2 rounded-xl text-xs">
                                                            Tutup
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                            Belum ada pengajuan event.
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