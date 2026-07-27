<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Detail Verifikasi Mitra: ') }} {{ $profile->organization_name ?? $profile->user->name }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Admin Sidebar -->
                <x-admin-sidebar />

                <!-- Main Content -->
                <div class="flex-1 space-y-6" x-data="{ previewUrl: null, previewTitle: '' }">
                    <!-- Profile Card -->
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">{{ $profile->organization_name ?? 'Komunitas' }}</h3>
                                <p class="text-xs text-slate-500">Penanggung Jawab: {{ $profile->owner_name ?? $profile->user->name }} ({{ $profile->user->email }})</p>
                            </div>
                            <span class="px-4 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full 
                                @if($profile->status->value === 'approved' || $profile->status->value === 'verified') bg-emerald-100 text-emerald-800
                                @elseif($profile->status->value === 'pending') bg-amber-100 text-amber-800
                                @elseif($profile->status->value === 'rejected') bg-rose-100 text-rose-800
                                @else bg-gray-100 text-gray-800 @endif">
                                STATUS: {{ strtoupper($profile->status->value) }}
                            </span>
                        </div>

                        <!-- Detail Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-slate-700">
                            <div>
                                <span class="font-bold text-slate-400 block uppercase text-[10px]">Nomor Telepon</span>
                                <span class="text-slate-900 font-semibold">{{ $profile->phone ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="font-bold text-slate-400 block uppercase text-[10px]">Tanggal Pendaftaran</span>
                                <span class="text-slate-900 font-semibold">{{ $profile->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="font-bold text-slate-400 block uppercase text-[10px]">Alamat Organisasi</span>
                                <span class="text-slate-900 font-semibold">{{ $profile->address ?? '-' }}</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="font-bold text-slate-400 block uppercase text-[10px]">Deskripsi Organisasi</span>
                                <p class="text-slate-700 mt-1 leading-relaxed">{{ $profile->description ?? 'Belum mengisi deskripsi.' }}</p>
                            </div>
                        </div>

                        <!-- Uploaded Documents -->
                        <div class="border-t border-gray-100 pt-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <h4 class="font-bold text-slate-900 text-sm">Dokumen Pendukung Verifikasi ({{ $profile->documents->count() }})</h4>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @forelse($profile->documents as $doc)
                                    @php
                                        $fileUrl = asset('storage/' . $doc->file_path);
                                    @endphp
                                    <div class="p-4 rounded-2xl border border-gray-200 bg-slate-50 space-y-3">
                                        <div class="space-y-1">
                                            <div class="flex items-center justify-between">
                                                <span class="font-extrabold text-slate-900 text-xs">{{ $doc->document_type }}</span>
                                                <span class="text-[10px] text-amber-700 font-extrabold">⏱ {{ $doc->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="text-[10px] text-slate-500 font-mono">Waktu Upload: {{ $doc->created_at->format('d M Y - H:i:s') }} WIB</div>
                                        </div>

                                        <div class="flex items-center gap-2 pt-2 border-t border-gray-200">
                                            <button type="button" @click="previewUrl = '{{ $fileUrl }}'; previewTitle = '{{ $doc->document_type }} (Diunggah: {{ $doc->created_at->format('d M Y - H:i:s') }} WIB - {{ $doc->created_at->diffForHumans() }})'" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold px-3 py-2 rounded-xl text-xs transition flex items-center justify-center gap-1 shadow-sm">
                                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>Pratinjau Modal</span>
                                            </button>

                                            <a href="{{ $fileUrl }}" target="_blank" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-3 py-2 rounded-xl text-xs transition flex items-center gap-1 shrink-0">
                                                <span>Buka File ↗</span>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-2 text-slate-400 text-xs italic bg-slate-50 p-6 rounded-2xl border border-dashed border-gray-200 text-center">
                                        Belum ada dokumen yang diunggah oleh mitra ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Modal Preview Dokumen -->
                        <div x-show="previewUrl" class="fixed inset-0 z-50 bg-slate-900/80 flex items-center justify-center p-4" x-cloak>
                            <div class="bg-white rounded-3xl p-6 max-w-4xl w-full space-y-4 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                    <h4 class="font-bold text-slate-900 text-sm" x-text="'Pratinjau Dokumen Admin: ' + previewTitle"></h4>
                                    <button type="button" @click="previewUrl = null" class="text-slate-400 hover:text-slate-600 font-bold text-lg px-2">✕</button>
                                </div>
                                <div class="flex-1 overflow-auto bg-slate-100 rounded-2xl p-2 flex items-center justify-center min-h-[400px]">
                                    <template x-if="previewUrl && previewUrl.toLowerCase().endsWith('.pdf')">
                                        <iframe :src="previewUrl" class="w-full h-[500px] rounded-xl"></iframe>
                                    </template>
                                    <template x-if="previewUrl && !previewUrl.toLowerCase().endsWith('.pdf')">
                                        <img :src="previewUrl" class="max-w-full max-h-[500px] object-contain rounded-xl shadow-md">
                                    </template>
                                </div>
                                <div class="flex justify-end gap-2 pt-2">
                                    <a :href="previewUrl" target="_blank" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
                                        Buka Tab Baru ↗
                                    </a>
                                    <button type="button" @click="previewUrl = null" class="bg-slate-900 text-white font-bold px-4 py-2 rounded-xl text-xs">
                                        Tutup Pratinjau
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Rejection Notes if Rejected -->
                        @if($profile->rejection_reason)
                            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-2xl space-y-1">
                                <h5 class="font-bold text-rose-900 text-xs">Catatan Penolakan Sebelumnya:</h5>
                                <p class="text-xs text-rose-800">{{ $profile->rejection_reason }}</p>
                            </div>
                        @endif

                        <!-- Action Approval / Rejection Buttons -->
                        <div class="border-t border-gray-100 pt-6 flex justify-end items-center gap-4">

                            <div class="flex items-center gap-3 w-full sm:w-auto" x-data="{ showRejectModal: false }">
                                <!-- Reject Form Trigger -->
                                <button type="button" @click="showRejectModal = true" class="w-1/2 sm:w-auto bg-rose-600 hover:bg-rose-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition shadow-sm">
                                    Tolak Verifikasi
                                </button>

                                <!-- Approve Form -->
                                <form action="{{ route('admin.organizer-verifications.approve', $profile) }}" method="POST" class="w-1/2 sm:w-auto">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" onclick="return confirm('Setujui dan verifikasi profil mitra ini?')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition shadow-sm">
                                        Setujui & Verifikasi Mitra
                                    </button>
                                </form>

                                <!-- Reject Reason Modal -->
                                <div x-show="showRejectModal" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4" x-cloak>
                                    <div class="bg-white rounded-3xl p-6 max-w-md w-full space-y-4 shadow-xl">
                                        <h4 class="font-bold text-slate-900 text-base">Alasan Penolakan Verifikasi</h4>
                                        <form action="{{ route('admin.organizer-verifications.reject', $profile) }}" method="POST" class="space-y-4">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <textarea name="rejection_reason" rows="4" required class="w-full text-xs border-gray-300 rounded-xl focus:border-rose-500 focus:ring-rose-500" placeholder="Tuliskan catatan kekurangan berkas / alasan penolakan..."></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="showRejectModal = false" class="bg-gray-100 hover:bg-gray-200 text-slate-700 font-bold px-4 py-2 rounded-xl text-xs">
                                                    Batal
                                                </button>
                                                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-4 py-2 rounded-xl text-xs">
                                                    Kirim Penolakan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
