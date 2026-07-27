<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Monitoring Pemesanan event') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Component -->
                <x-organizer-sidebar />

                <!-- Main Content Area -->
                <div class="flex-1 space-y-6">
                    <!-- Header Card -->
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h3 class="text-xl font-bold text-slate-900">Daftar Transaksi & Pemesanan Penonton</h3>
                            <p class="text-xs text-slate-500 mt-1">Pantau riwayat transaksi, detail tiket, dan status
                                pembayaran dari pengunjung event Anda.</p>
                        </div>

                        <!-- Statistics Grid -->
                        @php
                        $totalBookings = $bookings->total();
                        $paidCount = $bookings->filter(function($b) {
                        $st = is_object($b->status) ? $b->status->value : $b->status;
                        return in_array(strtolower((string)$st), ['paid', 'payment_completed', 'completed']);
                        })->count();

                        $pendingCount = $bookings->filter(function($b) {
                        $st = is_object($b->status) ? $b->status->value : $b->status;
                        return in_array(strtolower((string)$st), ['pending', 'waiting_payment', 'unpaid']);
                        })->count();
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="p-4 bg-slate-900 text-white rounded-2xl border border-slate-800">
                                <div class="text-xs font-semibold text-slate-400">Total Pemesanan</div>
                                <div class="text-2xl font-black mt-1">{{ number_format($totalBookings) }}</div>
                            </div>
                            <div class="p-4 bg-emerald-50 text-emerald-900 rounded-2xl border border-emerald-100">
                                <div class="text-xs font-semibold text-emerald-700">Lunas (Paid)</div>
                                <div class="text-2xl font-black mt-1">{{ number_format($paidCount) }}</div>
                            </div>
                            <div class="p-4 bg-amber-50 text-amber-900 rounded-2xl border border-amber-100">
                                <div class="text-xs font-semibold text-amber-700">Menunggu Pembayaran</div>
                                <div class="text-2xl font-black mt-1">{{ number_format($pendingCount) }}</div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table
                                class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-2xl overflow-hidden">
                                <thead class="bg-slate-900 text-white text-xs">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Kode Booking</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Nama Pemesan</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Total Bayar</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Status Pembayaran</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Waktu Transaksi</th>
                                        <th class="px-6 py-3.5 text-center font-bold uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100 text-xs text-slate-700">
                                    @forelse($bookings as $booking)
                                    @php
                                    $rawSt = is_object($booking->status) ? $booking->status->value : $booking->status;
                                    $st = strtolower((string)$rawSt);
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-6 py-4 font-mono font-bold text-amber-600">
                                            #{{ $booking->booking_code }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-900">
                                            {{ $booking->user->name ?? 'Pengunjung' }}
                                            <div class="text-[11px] font-normal text-slate-400">{{ $booking->user->email
                                                ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 font-extrabold text-slate-900">
                                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if(in_array($st, ['paid', 'payment_completed', 'completed']))
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                ✓ Lunas (Paid)
                                            </span>
                                            @elseif(in_array($st, ['pending', 'waiting_payment', 'unpaid']))
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                ⏳ Menunggu Pembayaran
                                            </span>
                                            @elseif(in_array($st, ['cancelled', 'expired']))
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                ✕ Dibatalkan / Kadaluarsa
                                            </span>
                                            @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-800">
                                                {{ ucfirst($st) }}
                                            </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-slate-500">
                                            {{ $booking->created_at ? $booking->created_at->format('d M Y, H:i') : '-'
                                            }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('organizer.bookings.show', $booking) }}"
                                                class="inline-flex items-center gap-1 text-slate-900 hover:text-amber-600 font-bold bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-xl transition">
                                                <span>Detail</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                            Belum ada pemesanan / transaksi dari penonton.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-4">
                            {{ $bookings->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>