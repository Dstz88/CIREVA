<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Monitoring & Verifikasi Transaksi Pembayaran') }}
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
                                <h3 class="text-xl font-bold text-slate-900">Daftar Transaksi Pembelian Tiket</h3>
                                <p class="text-xs text-slate-500 mt-1">Periksa bukti transfer dan verifikasi pembayaran tiket pengunjung.</p>
                            </div>

                            <!-- Filter Status -->
                            <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex items-center gap-2">
                                <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-xl focus:border-amber-500 focus:ring-amber-500">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success (Berhasil)</option>
                                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed (Gagal/Batal)</option>
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
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">No. Transaksi</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Pembeli / User</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Total Tagihan</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Bukti Pembayaran</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Status</th>
                                        <th class="px-6 py-3.5 text-right font-bold uppercase">Aksi Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100 text-xs text-slate-700">
                                    @forelse($transactions as $trx)
                                        @php
                                            $proof = $trx->paymentProof;
                                            $proofUrl = $proof ? (str_starts_with($proof->file_path, 'http') ? $proof->file_path : Storage::url($proof->file_path)) : null;
                                        @endphp
                                        <tr class="hover:bg-slate-50/80 transition" x-data="{ showRejectModal: false, showProofModal: false }">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-900">#{{ $trx->transaction_number ?? 'TRX-'.$trx->id }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $trx->created_at->format('d M Y H:i') }} WIB</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-900">{{ $trx->booking->user->name ?? '-' }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $trx->booking->user->email ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-slate-900">
                                                Rp {{ number_format($trx->amount ?? $trx->total_amount ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($proofUrl)
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" @click="showProofModal = true" class="relative group w-12 h-12 rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-slate-900 shrink-0">
                                                            <img src="{{ $proofUrl }}" alt="Bukti Transfer" class="w-full h-full object-cover group-hover:scale-105 transition duration-300 opacity-90">
                                                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-white text-xs">🔍</div>
                                                        </button>
                                                        <button type="button" @click="showProofModal = true" class="text-xs font-bold text-blue-900 hover:underline">
                                                            Lihat Bukti
                                                        </button>
                                                    </div>

                                                    <!-- Preview Modal -->
                                                    <div x-show="showProofModal" class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4 text-left" x-cloak>
                                                        <div class="bg-white rounded-3xl p-6 max-w-lg w-full space-y-4 shadow-2xl relative" @click.outside="showProofModal = false">
                                                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                                <div>
                                                                    <h4 class="font-extrabold text-slate-900 text-base">Bukti Transfer Pembayaran</h4>
                                                                    <p class="text-xs text-slate-500">#{{ $trx->transaction_number }} &bull; {{ $trx->booking->user->name ?? 'User' }}</p>
                                                                </div>
                                                                <button type="button" @click="showProofModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
                                                            </div>

                                                            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 flex items-center justify-center max-h-96">
                                                                @if(str_ends_with(strtolower($proofUrl), '.pdf'))
                                                                    <div class="p-8 text-center space-y-3">
                                                                        <span class="text-4xl">📄</span>
                                                                        <p class="text-xs text-slate-300 font-bold">Dokumen Bukti Transfer (PDF)</p>
                                                                        <a href="{{ $proofUrl }}" target="_blank" class="inline-block bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-4 py-2 rounded-xl text-xs">
                                                                            Buka Berkas PDF
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <img src="{{ $proofUrl }}" alt="Bukti Transfer" class="max-h-96 w-auto object-contain mx-auto">
                                                                @endif
                                                            </div>

                                                            <div class="flex items-center justify-between text-xs text-slate-500 pt-2">
                                                                <span>Diunggah pada: <strong>{{ $proof->uploaded_at ? \Carbon\Carbon::parse($proof->uploaded_at)->format('d M Y H:i') : '-' }} WIB</strong></span>
                                                                <a href="{{ $proofUrl }}" target="_blank" class="font-bold text-blue-950 hover:underline">Unduh Berkas &rarr;</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-slate-400 italic text-[11px]">Belum Upload</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 inline-flex text-[11px] leading-5 font-bold rounded-full 
                                                    @if($trx->status->value === 'success' || $trx->status->value === 'paid') bg-emerald-100 text-emerald-800
                                                    @elseif($trx->status->value === 'pending') bg-amber-100 text-amber-800
                                                    @elseif($trx->status->value === 'failed') bg-rose-100 text-rose-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ strtoupper($trx->status->value) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right space-x-2">
                                                @if($trx->status->value !== 'success' && $trx->status->value !== 'paid')
                                                    <!-- Reject Button -->
                                                    <button type="button" @click="showRejectModal = true" class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-xl font-bold transition inline-block text-xs">
                                                        Tolak
                                                    </button>

                                                    <!-- Approve / Verify Form -->
                                                    <form action="{{ route('admin.transactions.verify', $trx) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" onclick="return confirm('Verifikasi pembayaran dan terbitkan e-tiket?')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-xl font-bold transition inline-block text-xs">
                                                            Verifikasi Lunas
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-emerald-700 font-bold">✓ Terverifikasi Lunas</span>
                                                @endif

                                                <!-- Reject Modal -->
                                                <div x-show="showRejectModal" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4 text-left" x-cloak>
                                                    <div class="bg-white rounded-3xl p-6 max-w-md w-full space-y-4 shadow-xl" @click.outside="showRejectModal = false">
                                                        <h4 class="font-bold text-slate-900 text-base">Alasan Penolakan Transaksi</h4>
                                                        <form action="{{ route('admin.transactions.reject', $trx) }}" method="POST" class="space-y-4">
                                                            @csrf
                                                            @method('PUT')
                                                            <div>
                                                                <textarea name="notes" rows="4" required class="w-full text-xs border-gray-300 rounded-xl focus:border-rose-500 focus:ring-rose-500" placeholder="Tuliskan catatan kenapa bukti pembayaran ditolak (misal: nominal kurang, bukti buram)..."></textarea>
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
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                                Belum ada data transaksi pembayaran.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-4">
                            {{ $transactions->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
