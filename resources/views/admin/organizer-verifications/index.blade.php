<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Verifikasi Profil Mitra') }}
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
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Daftar Pengajuan Profil Mitra</h3>
                                <p class="text-xs text-slate-500 mt-1">Tinjau kelengkapan profil & dokumen pendukung untuk menyetujui akun organizer.</p>
                            </div>
                            
                            <!-- Filter Status -->
                            <form method="GET" action="{{ route('admin.organizer-verifications.index') }}" class="flex items-center gap-2">
                                <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-xl focus:border-amber-500 focus:ring-amber-500">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
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
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Nama Organisasi / Pemilik</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Kontak</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Dokumen</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Status</th>
                                        <th class="px-6 py-3.5 text-right font-bold uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100 text-xs text-slate-700">
                                    @forelse($profiles as $profile)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-900">{{ $profile->organization_name ?? $profile->user->name }}</div>
                                                <div class="text-[11px] text-slate-400">Penanggung Jawab: {{ $profile->owner_name ?? $profile->user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div>{{ $profile->user->email }}</div>
                                                <div class="text-slate-400">{{ $profile->phone ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                                    {{ $profile->documents->count() }} File
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 inline-flex text-[11px] leading-5 font-bold rounded-full 
                                                    @if($profile->status->value === 'approved' || $profile->status->value === 'verified') bg-emerald-100 text-emerald-800
                                                    @elseif($profile->status->value === 'pending') bg-amber-100 text-amber-800
                                                    @elseif($profile->status->value === 'rejected') bg-rose-100 text-rose-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ strtoupper($profile->status->value) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right space-x-2">
                                                <a href="{{ route('admin.organizer-verifications.show', $profile) }}" 
                                                   class="bg-slate-900 hover:bg-slate-800 text-white px-3 py-1.5 rounded-xl font-bold transition inline-block">
                                                    Review Details &rarr;
                                                </a>

                                                <form action="{{ route('admin.organizer-verifications.destroy', $profile) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus profil mitra organizer ini?')" 
                                                            class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-xl font-bold transition inline-block">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                                Belum ada pengajuan verifikasi mitra.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-4">
                            {{ $profiles->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
