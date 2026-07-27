<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Rincian Pemesanan Tiket') }}
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

                        @php
                        $st = is_object($booking->status) ? $booking->status->value : $booking->status;
                        @endphp

                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="text-xl font-black text-slate-900">Booking #{{ $booking->booking_code }}</h3>
                                <p class="text-xs text-slate-500 mt-1">
                                    Dipesan pada {{ $booking->created_at ? $booking->created_at->format('d M Y, H:i') :
                                    '-' }} oleh <strong class="text-slate-700">{{ $booking->user->name ?? 'Pengunjung'
                                        }}</strong> ({{ $booking->user->email ?? '-' }})
                                </p>
                            </div>
                            <div>
                                @if($st === 'paid')
                                <span
                                    class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    ✓ Lunas (Paid)
                                </span>
                                @elseif(in_array($st, ['pending', 'waiting_payment']))
                                <span
                                    class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                    ⏳ Menunggu Pembayaran
                                </span>
                                @elseif(in_array($st, ['cancelled', 'expired']))
                                <span
                                    class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-rose-100 text-rose-800 border border-rose-200">
                                    ✕ Dibatalkan / Kadaluarsa
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-gray-100 text-gray-800">
                                    {{ ucfirst($st) }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Item Tiket -->
                        <div class="space-y-3">
                            <h4 class="font-bold text-xs text-slate-700 uppercase tracking-wider">Rincian Tiket Dipesan
                            </h4>
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-2xl overflow-hidden text-xs">
                                    <thead class="bg-slate-900 text-white font-bold uppercase">
                                        <tr>
                                            <th class="px-6 py-3.5 text-left">Nama event</th>
                                            <th class="px-6 py-3.5 text-left">Jenis Tiket</th>
                                            <th class="px-6 py-3.5 text-right">Harga Satuan</th>
                                            <th class="px-6 py-3.5 text-center">Jumlah</th>
                                            <th class="px-6 py-3.5 text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100 text-slate-700">
                                        @foreach($booking->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 font-bold text-slate-900">
                                                {{ $item->ticket->event->title ?? 'event' }}
                                            </td>
                                            <td class="px-6 py-4 text-slate-600">
                                                {{ $item->ticket->name ?? 'Tiket' }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-mono">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-center font-bold">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-bold text-slate-900 font-mono">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-slate-50 border-t border-gray-200">
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-bold text-slate-700">Total
                                                Pembayaran</td>
                                            <td
                                                class="px-6 py-4 text-right font-extrabold text-amber-600 text-sm font-mono">
                                                Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                            <a href="{{ route('organizer.bookings.index') }}"
                                class="text-xs text-slate-500 font-bold hover:underline">
                                &larr; Kembali ke Monitoring Pemesanan
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>