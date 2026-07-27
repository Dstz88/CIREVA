<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Monitoring Laporan Penjualan & Komisi') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Component -->
                <x-organizer-sidebar />

                <!-- Main Content Area -->
                <div class="flex-1 space-y-6">

                    <!-- Analytics Metric Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
                            <span class="text-[11px] font-bold uppercase text-slate-400">Total Pendapatan Kotor</span>
                            <div class="text-2xl font-black text-slate-900">
                                Rp {{ number_format($grossRevenue ?? 0, 0, ',', '.') }}
                            </div>
                            <span class="text-[10px] text-slate-400">Total omset tiket terjual</span>
                        </div>

                        <div class="bg-amber-50/60 border border-amber-200 p-6 rounded-3xl shadow-sm space-y-2">
                            <span class="text-[11px] font-bold uppercase text-amber-800">Komisi Platform (15%)</span>
                            <div class="text-2xl font-black text-amber-700">
                                - Rp {{ number_format($platformFee ?? 0, 0, ',', '.') }}
                            </div>
                            <span class="text-[10px] text-amber-800">Bagi hasil SPK 15%</span>
                        </div>

                        <div
                            class="bg-slate-900 text-white p-6 rounded-3xl shadow-md border border-slate-800 space-y-2">
                            <span class="text-[11px] font-bold uppercase text-amber-400">Pendapatan Bersih Mitra
                                (85%)</span>
                            <div class="text-2xl font-black text-emerald-400">
                                Rp {{ number_format($netRevenue ?? 0, 0, ',', '.') }}
                            </div>
                            <span class="text-[10px] text-slate-400">Net payout ditransfer ke mitra</span>
                        </div>
                    </div>

                    <!-- Report Filter & Table -->
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-6">
                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Rincian Laporan Pemesanan</h3>
                                <p class="text-xs text-slate-500 mt-1">Filter laporan berdasarkan rentang tanggal event.
                                </p>
                            </div>

                            <!-- Date Filter Form -->
                            <form method="GET" action="{{ route('organizer.reports.index') }}"
                                class="flex flex-wrap items-center gap-2">
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="text-xs border-gray-300 rounded-xl focus:border-amber-500 focus:ring-amber-500">
                                <span class="text-xs text-slate-400">s/d</span>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="text-xs border-gray-300 rounded-xl focus:border-amber-500 focus:ring-amber-500">
                                <button type="submit"
                                    class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-xl text-xs font-bold transition">
                                    Filter
                                </button>
                            </form>
                        </div>

                        <div class="overflow-x-auto">
                            <table
                                class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-2xl overflow-hidden">
                                <thead class="bg-slate-900 text-white text-xs">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">No. Booking</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Pembeli</th>
                                        <th class="px-6 py-3.5 text-left font-bold uppercase">Tanggal</th>
                                        <th class="px-6 py-3.5 text-right font-bold uppercase">Nilai Transaksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100 text-xs text-slate-700">
                                    @forelse($bookings as $booking)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-6 py-4 font-bold text-slate-900">
                                            #{{ $booking->booking_number ?? $booking->id }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900">{{ $booking->user->name ?? 'Guest' }}
                                            </div>
                                            <div class="text-[11px] text-slate-400">{{ $booking->user->email ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500">
                                            {{ $booking->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-900">
                                            Rp {{ number_format($booking->transaction->amount ?? $booking->total_amount
                                            ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                            Belum ada transaksi pemesanan yang tercatat.
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