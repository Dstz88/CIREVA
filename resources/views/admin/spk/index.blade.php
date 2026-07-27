<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Persetujuan Surat Perjanjian Kerjasama (SPK 15%)') }}
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
                        
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('images/LOGO CIREVA.jpeg') }}" alt="CIREVA Logo" class="h-10 w-auto object-contain rounded-lg shadow-sm">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900">Surat Perjanjian Kerjasama (SPK)</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Kelola persetujuan dokumen kesepakatan bagi hasil komisi platform 15% dengan Mitra Organizer.</p>
                                </div>
                            </div>

                            <!-- Filter Status -->
                            <form method="GET" action="{{ route('admin.spk.index') }}" class="flex items-center gap-2">
                                <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-xl focus:border-amber-500 focus:ring-amber-500">
                                    <option value="">Semua Status</option>
                                    <option value="signed" {{ request('status') === 'signed' ? 'selected' : '' }}>Ditandatangani Mitra</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>
                        </div>

                        <!-- Flash Messages -->
                        @if(session('success'))
                            <div class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-xs font-bold border border-emerald-200">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('warning'))
                            <div class="bg-amber-50 text-amber-800 p-4 rounded-2xl text-xs font-bold border border-amber-200">
                                {{ session('warning') }}
                            </div>
                        @endif

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-2xl overflow-hidden">
                                <thead class="bg-slate-900 text-white text-xs">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">No. SPK / Version</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Mitra Organizer</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Komisi</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Status</th>
                                        <th class="px-6 py-3.5 text-right font-bold uppercase">Aksi Hapus</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100 text-xs text-slate-700">
                                    @forelse($agreements as $agreement)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-900">{{ $agreement->agreement_number ?? 'SPK-'.str_pad($agreement->id, 5, '0', STR_PAD_LEFT) }}</div>
                                                <div class="text-[11px] text-slate-400">Ver: {{ $agreement->version ?? 'v1.0' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-900">{{ $agreement->organizerProfile->organization_name ?? $agreement->organizerProfile->user->name ?? '-' }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $agreement->organizerProfile->user->email ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-900">
                                                    15% Platform Fee
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 inline-flex text-[11px] leading-5 font-bold rounded-full 
                                                    @if($agreement->status->value === 'approved') bg-emerald-100 text-emerald-800
                                                    @elseif($agreement->status->value === 'signed' || $agreement->status->value === 'under_review') bg-blue-100 text-blue-800
                                                    @elseif($agreement->status->value === 'rejected') bg-rose-100 text-rose-800
                                                    @else bg-amber-100 text-amber-800 @endif">
                                                    {{ strtoupper($agreement->status->value) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <form action="{{ route('admin.spk.destroy', $agreement) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen SPK ini?')" class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-xl font-bold transition inline-block text-xs">
                                                        Hapus SPK
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                                Belum ada Surat Perjanjian Kerjasama (SPK) yang terdaftar.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-4">
                            {{ $agreements->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>