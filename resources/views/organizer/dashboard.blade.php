<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Mitra Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Component -->
                <x-organizer-sidebar />

                <!-- Main Content Area -->
                <div class="flex-1 space-y-6">
                    @php
                    $profile = Auth::user()->organizerProfile;
                    $status = $profile?->status->value ?? (string) $profile?->status;
                    $isVerified = in_array(strtolower($status), ['approved', 'verified']);

                    $hasOrgName = !empty($profile?->organization_name);
                    $hasOwnerName = !empty($profile?->owner_name);
                    $hasPhone = !empty($profile?->phone);
                    $hasDocs = ($profile?->documents->count() ?? 0) > 0;
                    @endphp

                    @if(!$isVerified)
                    <!-- Warning & Direct Profile Completion Card -->
                    <div
                        class="bg-gradient-to-br from-amber-500 via-amber-600 to-amber-700 text-white rounded-3xl p-6 md:p-8 shadow-lg border border-amber-400/30 relative overflow-hidden space-y-6">
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-white/20 pb-6">
                            <div class="space-y-1">
                                <div
                                    class="inline-flex items-center gap-2 bg-slate-950/40 text-amber-200 text-[11px] font-extrabold uppercase px-3 py-1 rounded-full border border-amber-300/30">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span>AKUN BELUM DIVERIFIKASI</span>
                                </div>
                                <h3 class="text-2xl font-black tracking-tight text-white">Lengkapi Profil & Upload
                                    Dokumen Mitra</h3>
                                <p class="text-xs text-amber-100 max-w-2xl leading-relaxed">
                                    Akun Anda membutuhkan verifikasi kelengkapan profil dari Admin agar fitur pembuatan
                                    event budaya, manajemen tiket, dan pencairan komisi dapat aktif.
                                </p>
                            </div>
                            <a href="{{ route('organizer.profile.show') }}"
                                class="bg-slate-950 hover:bg-slate-900 text-white font-extrabold px-6 py-3.5 rounded-2xl text-xs transition shadow-md shrink-0 flex items-center gap-2">
                                <span>Lengkapi Profil Sekarang</span>
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>

                        <!-- Checklist Kelengkapan Profil -->
                        <div class="space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-amber-100">Checklist Syarat
                                Verifikasi:</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div
                                    class="flex items-center gap-2.5 bg-slate-950/20 px-4 py-2.5 rounded-xl border border-white/10">
                                    <span
                                        class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[10px] {{ $hasOrgName ? 'bg-emerald-400 text-slate-950' : 'bg-white/20 text-white' }}">
                                        {{ $hasOrgName ? '✓' : '1' }}
                                    </span>
                                    <span
                                        class="{{ $hasOrgName ? 'line-through text-amber-200' : 'font-semibold text-white' }}">Detail
                                        & Deskripsi Organisasi</span>
                                </div>

                                <div
                                    class="flex items-center gap-2.5 bg-slate-950/20 px-4 py-2.5 rounded-xl border border-white/10">
                                    <span
                                        class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[10px] {{ $hasOwnerName ? 'bg-emerald-400 text-slate-950' : 'bg-white/20 text-white' }}">
                                        {{ $hasOwnerName ? '✓' : '2' }}
                                    </span>
                                    <span
                                        class="{{ $hasOwnerName ? 'line-through text-amber-200' : 'font-semibold text-white' }}">Nama
                                        Penanggung Jawab (Sesuai KTP)</span>
                                </div>

                                <div
                                    class="flex items-center gap-2.5 bg-slate-950/20 px-4 py-2.5 rounded-xl border border-white/10">
                                    <span
                                        class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[10px] {{ $hasPhone ? 'bg-emerald-400 text-slate-950' : 'bg-white/20 text-white' }}">
                                        {{ $hasPhone ? '✓' : '3' }}
                                    </span>
                                    <span
                                        class="{{ $hasPhone ? 'line-through text-amber-200' : 'font-semibold text-white' }}">Nomor
                                        Handphone / WhatsApp Penanggung Jawab</span>
                                </div>

                                <div
                                    class="flex items-center gap-2.5 bg-slate-950/20 px-4 py-2.5 rounded-xl border border-white/10">
                                    <span
                                        class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[10px] {{ $hasDocs ? 'bg-emerald-400 text-slate-950' : 'bg-white/20 text-white' }}">
                                        {{ $hasDocs ? '✓' : '4' }}
                                    </span>
                                    <span
                                        class="{{ $hasDocs ? 'line-through text-amber-200' : 'font-semibold text-white' }}">Upload
                                        Dokumen Verifikasi (KTP / Surat Izin)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-2">
                        <h3 class="text-xl font-bold text-slate-900">Selamat datang, {{ $profile->organization_name ??
                            Auth::user()->name }}!</h3>
                        <p class="text-xs text-slate-500">Gunakan menu sidebar di samping untuk mengelola profil, surat
                            kerjasama (SPK), event, tiket, dan pemesanan.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profil Card -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-slate-900 text-base">Profil Mitra</h4>
                                <span
                                    class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">1</span>
                            </div>
                            <p class="text-xs text-slate-500">Lengkapi data organisasi dan unggah dokumen pendukung
                                verifikasi.</p>
                            <a href="{{ route('organizer.profile.show') }}"
                                class="inline-flex items-center text-xs font-bold text-amber-700 hover:text-amber-800">
                                Kelola Profil &rarr;
                            </a>
                        </div>

                        <!-- SPK Card -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-slate-900 text-base">Surat Kerjasama (SPK)</h4>
                                <span
                                    class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">2</span>
                            </div>
                            <p class="text-xs text-slate-500">Tinjau dan setujui syarat & ketentuan kerjasama (komisi
                                15%).</p>
                            <a href="{{ route('organizer.spk.index') }}"
                                class="inline-flex items-center text-xs font-bold text-amber-700 hover:text-amber-800">
                                Cek SPK &rarr;
                            </a>
                        </div>

                        <!-- events Card -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-slate-900 text-base">event Saya</h4>
                                <span
                                    class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">3</span>
                            </div>
                            <p class="text-xs text-slate-500">Buat dan publikasikan event kebudayaan Cirebon.</p>
                            <a href="{{ route('organizer.events.index') }}"
                                class="inline-flex items-center text-xs font-bold text-amber-700 hover:text-amber-800">
                                Kelola event &rarr;
                            </a>
                        </div>

                        <!-- Bookings Card -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-slate-900 text-base">Monitoring Pemesanan</h4>
                                <span
                                    class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">4</span>
                            </div>
                            <p class="text-xs text-slate-500">Pantau transaksi dan status tiket pembeli.</p>
                            <a href="{{ route('organizer.bookings.index') }}"
                                class="inline-flex items-center text-xs font-bold text-amber-700 hover:text-amber-800">
                                Lihat Pemesanan &rarr;
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>