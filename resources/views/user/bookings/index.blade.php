<x-app-layout>
    <div class="flex min-h-screen bg-slate-50">
        <!-- Sidebar User -->
        <x-user-sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar Search & Profile -->
            <header
                class="bg-white border-b border-slate-100 py-4 px-8 flex items-center justify-end sticky top-0 z-10 shadow-sm">
                <div class="flex items-center gap-4">
                    <x-notification-bell />

                    <div class="flex items-center gap-3 pl-2 border-l border-slate-100">
                        <span class="text-xs font-semibold text-slate-700">Halo, <span
                                class="font-bold text-slate-900">{{ Auth::user()->name }}</span>!</span>
                        <x-user-avatar size="w-9 h-9" textSize="text-xs" />
                    </div>
                </div>
            </header>

            <!-- Main Body -->
            <main class="p-8 flex-1 space-y-6">

                <!-- Page Title -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Riwayat Pemesanan Tiket</h1>
                        <p class="text-xs text-slate-500 mt-1">Lihat dan kelola seluruh riwayat pemesanan tiket event
                            budaya Anda.</p>
                    </div>


                </div>

                @if(session('success'))
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <!-- Filter Status Tabs -->
                <div class="flex items-center gap-2 overflow-x-auto text-xs font-semibold pb-1">
                    <a href="{{ route('user.bookings.index') }}"
                        class="px-4 py-2 rounded-full transition {{ !request('status') ? 'bg-blue-950 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Semua Status
                    </a>
                    <a href="{{ route('user.bookings.index', ['status' => 'pending']) }}"
                        class="px-4 py-2 rounded-full transition {{ request('status') === 'pending' ? 'bg-blue-950 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Menunggu Pembayaran
                    </a>
                    <a href="{{ route('user.bookings.index', ['status' => 'paid']) }}"
                        class="px-4 py-2 rounded-full transition {{ request('status') === 'paid' ? 'bg-blue-950 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Lunas
                    </a>
                    <a href="{{ route('user.bookings.index', ['status' => 'cancelled']) }}"
                        class="px-4 py-2 rounded-full transition {{ request('status') === 'cancelled' ? 'bg-blue-950 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Dibatalkan
                    </a>
                </div>

                @if(count($bookings) > 0)

                <!-- Bookings List -->
                <div class="space-y-4">
                    @foreach($bookings as $booking)
                    @php
                    $statusVal = is_object($booking->status) ? $booking->status->value : $booking->status;
                    $firstItem = $booking->items->first();
                    $event = $firstItem?->ticket?->event;
                    @endphp
                    <div
                        class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-3 flex-1">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="font-extrabold text-xs text-slate-900 bg-slate-100 px-3 py-1 rounded-lg">
                                    {{ $booking->booking_code }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ $booking->created_at->format('d M Y, H:i') }} WIB
                                </span>
                                <!-- Status Badge -->
                                @if(in_array($statusVal, ['pending', 'waiting_payment']))
                                <span
                                    class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase">
                                    Menunggu Pembayaran
                                </span>
                                @elseif(in_array($statusVal, ['paid', 'payment_completed', 'confirmed']))
                                <span
                                    class="bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase">
                                    Lunas
                                </span>
                                @elseif($statusVal === 'pending_verification')
                                <span
                                    class="bg-blue-100 text-blue-800 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase">
                                    Menunggu Verifikasi
                                </span>
                                @else
                                <span
                                    class="bg-slate-100 text-slate-700 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase">
                                    {{ ucfirst($statusVal) }}
                                </span>
                                @endif
                            </div>

                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base">
                                    {{ $event->title ?? 'Tiket event Budaya' }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $firstItem->ticket->name ?? 'Tiket Masuk' }} &bull; {{ $firstItem->quantity ?? 1
                                    }}x Tiket
                                </p>
                            </div>
                        </div>

                        <!-- Amount & Actions -->
                        <div
                            class="flex flex-col sm:flex-row md:flex-col items-end justify-between gap-3 shrink-0 border-t md:border-t-0 pt-3 md:pt-0 border-slate-100">
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Total Pembayaran</span>
                                <p class="text-lg font-black text-slate-900">
                                    Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                @if(in_array($statusVal, ['pending', 'waiting_payment']))
                                <a href="{{ route('user.bookings.show', $booking) }}"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold px-4 py-2.5 rounded-xl text-xs transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>Lanjutkan Pembayaran</span>
                                </a>
                                @elseif(in_array($statusVal, ['paid', 'payment_completed', 'confirmed']))
                                <a href="{{ route('user.tickets.index') }}"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2 2 2 0 000 4 2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 00-2-2 2 2 0 000-4 2 2 0 002-2V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                    <span>E-Ticket Saya</span>
                                </a>
                                @endif

                                <a href="{{ route('user.bookings.show', $booking) }}"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>

                @else
                <!-- Empty State: User Has Not Ordered Any Ticket Yet -->
                <div
                    class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm max-w-2xl mx-auto space-y-6 my-8">
                    <div
                        class="w-20 h-20 bg-amber-50 text-amber-600 rounded-3xl flex items-center justify-center mx-auto text-3xl shadow-inner border border-amber-100">
                        🎟️
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-xl font-black text-slate-900">Belum Ada Pesanan Tiket</h3>
                        <p class="text-xs text-slate-500 leading-relaxed max-w-md mx-auto">
                            Anda belum pernah memesan tiket event budaya. Jelajahi berbagai pilihan pagelaran seni dan
                            festival budaya menarik di Kota Cirebon.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('events.index') }}"
                            class="inline-flex items-center gap-2 bg-blue-950 hover:bg-blue-900 text-white font-extrabold px-8 py-3.5 rounded-2xl text-xs transition shadow-md">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2 2 2 0 000 4 2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 00-2-2 2 2 0 000-4 2 2 0 002-2V7a2 2 0 00-2-2H5z" />
                            </svg>
                            <span>Pesan Tiket Sekarang</span>
                        </a>
                    </div>
                </div>
                @endif

            </main>
        </div>
    </div>
</x-app-layout>