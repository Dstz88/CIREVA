<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">

            <!-- Admin Sidebar Component -->
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <main class="flex-1 space-y-6">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.index') }}"
                                    class="text-xs font-bold text-amber-600 hover:underline">← Kembali ke Kategori</a>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mt-1">Detail Kategori: {{ $category->name }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Daftar seluruh event yang didaftarkan Mitra dalam
                                kategori ini.</p>
                        </div>
                        <span
                            class="text-xs font-bold text-amber-800 bg-amber-50 px-4 py-2 rounded-full border border-amber-100">
                            Total: {{ $category->events->count() }} event
                        </span>
                    </div>

                    <!-- event Table -->
                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-2xl overflow-hidden">
                            <thead class="bg-slate-900 text-white text-xs">
                                <tr>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Nama Mitra</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Nama event</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Lokasi</th>
                                    <th class="px-6 py-3.5 text-left font-bold uppercase">Status event</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 text-xs text-slate-700">
                                @forelse($category->events as $event)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">
                                            {{ $event->organizerProfile->organization_name ?? '-' }}
                                        </div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $event->organizerProfile->user->email ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $event->title }}</div>
                                        <div class="text-[11px] text-amber-700 font-semibold">{{
                                            $event->created_at->format('d M Y H:i') }}</div>
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
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                        Belum ada event yang didaftarkan untuk kategori ini.
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