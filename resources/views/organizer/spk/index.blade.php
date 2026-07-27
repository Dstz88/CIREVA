<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Surat Perjanjian Kerjasama (SPK)') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Component -->
                <x-organizer-sidebar />

                <!-- Main Content Area -->
                <div class="flex-1 space-y-6">
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-6">

                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Dokumen SPK & Bagi Hasil</h3>
                                <p class="text-xs text-slate-500 mt-1">Perjanjian resmi kemitraan antara Platform CIREVA
                                    dan Mitra Penyelenggara.</p>
                            </div>

                            <a href="{{ route('organizer.spk.export-pdf') }}" target="_blank"
                                class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Cetak / Ekspor PDF SPK</span>
                            </a>
                        </div>

                        <!-- SPK Preview Box -->
                        <div
                            class="bg-slate-50 rounded-2xl p-6 md:p-8 border border-slate-200 space-y-6 text-slate-800 text-xs leading-relaxed">
                            <div class="text-center space-y-3 pb-6 border-b border-slate-200">
                                <img src="{{ asset('images/LOGO CIREVA.jpeg') }}" alt="CIREVA Logo"
                                    class="h-16 w-auto mx-auto object-contain rounded-xl shadow-sm">
                                <div>
                                    <h4 class="font-black text-base text-slate-900 uppercase tracking-wide">SURAT
                                        PERJANJIAN KERJASAMA (SPK)</h4>
                                    <div class="text-slate-500 font-mono text-xs mt-0.5">
                                        Nomor: {{ $agreement->agreement_number ?? 'SPK-00001-15PCT' }}
                                    </div>
                                </div>
                                <div
                                    class="inline-block bg-emerald-100 text-emerald-800 font-extrabold px-4 py-1 rounded-full text-xs shadow-sm">
                                    ✓ TELAH DITANDATANGANI & APPROVED
                                </div>
                            </div>

                            <div class="space-y-4">
                                <p><strong>Pihak Pertama:</strong> Pengelola Sistem Platform Kebudayaan CIREVA</p>
                                <p><strong>Pihak Kedua:</strong> {{ $profile->organization_name ?? Auth::user()->name }}
                                    (Penanggung Jawab: {{ $profile->owner_name ?? Auth::user()->name }})</p>

                                <h5 class="font-bold text-slate-900 border-b pb-1">Pasal 1: Ketentuan Pembagian Komisi
                                </h5>
                                <p>Pihak Kedua menyetujui pemotongan bagi hasil sebesar <strong>15% (lima belas
                                        persen)</strong> untuk setiap transaksi penjualan tiket event kebudayaan melalui
                                    sistem CIREVA sebagai biaya operasional platform.</p>

                                <h5 class="font-bold text-slate-900 border-b pb-1">Pasal 2: Kewajiban Pihak Kedua</h5>
                                <p>Pihak Kedua wajib menyediakan informasi event yang valid, akurat, dan menjaga
                                    kelancaran pelaksanaan event kebudayaan Cirebon sesuai jadwal yang dipublikasikan.
                                </p>
                            </div>

                            <div
                                class="pt-6 border-t border-slate-200 flex justify-between items-center text-[11px] text-slate-500">
                                <div>Versi Dokumen: {{ $agreement->version ?? 'v1.0' }}</div>
                                <div>Ditandatangani Pada: {{ optional($agreement->signed_at)->format('d M Y H:i') ??
                                    date('d M Y H:i') }}</div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>