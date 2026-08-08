<x-app-layout>
    <div class="flex min-h-screen bg-slate-50">
        <!-- Sidebar User -->
        <x-user-sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar Search & Profile -->
            <header
                class="bg-white border-b border-slate-100 py-4 px-8 flex items-center justify-between sticky top-0 z-10 shadow-sm">
                <div class="relative w-full max-w-md">
                    <input type="text" placeholder="Cari event, tempat, atau kategori..."
                        class="w-full bg-slate-50 text-xs border-0 rounded-full py-2.5 pl-5 pr-10 focus:ring-2 focus:ring-blue-900 focus:bg-white transition placeholder-slate-400">
                    <svg class="w-4 h-4 text-slate-400 absolute right-4 top-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex items-center gap-4">
                    <x-notification-bell />

                    <div class="flex items-center gap-3 pl-2 border-l border-slate-100">
                        <span class="text-xs font-semibold text-slate-700">Halo, <span
                                class="font-bold text-slate-900">{{ Auth::user()->name }}</span>!</span>
                        <x-user-avatar size="w-9 h-9" textSize="text-xs" />
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="p-8 flex-1 space-y-6">

                <!-- Breadcrumb & Title -->
                <div class="space-y-2">
                    <nav class="flex text-xs text-slate-500 gap-2 items-center">
                        <a href="{{ route('user.dashboard') }}" class="hover:text-slate-900">Dashboard</a>
                        <span>&rsaquo;</span>
                        <a href="{{ route('user.bookings.index') }}" class="hover:text-slate-900">Pesan Tiket</a>
                        <span>&rsaquo;</span>
                        <span class="font-bold text-slate-800">Pembayaran {{ $booking->booking_code }}</span>
                    </nav>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1">
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Halaman Pembayaran</h1>
                            <p class="text-xs text-slate-500 mt-0.5">Selesaikan pembayaran & upload bukti pembayaran
                                untuk verifikasi Admin.</p>
                        </div>

                        <!-- Status Badge -->
                        @php
                        $statusVal = is_object($booking->status) ? $booking->status->value : $booking->status;
                        $hasProof = $booking->transaction && $booking->transaction->paymentProof;
                        @endphp
                        <div>
                            @if(in_array($statusVal, ['paid', 'payment_completed', 'confirmed']))
                            <span
                                class="bg-emerald-100 text-emerald-800 text-xs font-extrabold px-4 py-2 rounded-full uppercase shadow-sm inline-flex items-center gap-1.5">
                                <span>✅</span> Pembayaran Lunas
                            </span>
                            @elseif(in_array($statusVal, ['cancelled', 'expired']))
                            <span
                                class="bg-rose-100 text-rose-800 text-xs font-extrabold px-4 py-2 rounded-full uppercase shadow-sm inline-flex items-center gap-1.5">
                                <span>❌</span> Pemesanan Dibatalkan
                            </span>
                            @elseif($hasProof)
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-extrabold px-4 py-2 rounded-full uppercase shadow-sm inline-flex items-center gap-1.5">
                                <span>⏳</span> Menunggu Verifikasi Admin
                            </span>
                            @else
                            <span
                                class="bg-amber-100 text-amber-900 text-xs font-extrabold px-4 py-2 rounded-full uppercase shadow-sm inline-flex items-center gap-1.5">
                                <span>⚠️</span> Menunggu Pembayaran
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div
                    class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-medium space-y-1">
                    @foreach($errors->all() as $err)
                    <p>⚠️ {{ $err }}</p>
                    @endforeach
                </div>
                @endif

                <!-- MAIN GRID: LEFT (ORDER & INSTRUCTIONS) | RIGHT (UPLOAD FORM) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- LEFT COLUMN (2 COLS) -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Ringkasan Pesanan Box -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <h3 class="font-extrabold text-slate-900 text-base">Ringkasan Pesanan</h3>
                                <span class="font-extrabold text-xs text-blue-950 bg-slate-100 px-3 py-1 rounded-lg">
                                    {{ $booking->booking_code }}
                                </span>
                            </div>

                            <div class="space-y-4">
                                @foreach($booking->items as $item)
                                @php
                                $event = $item->ticket->event ?? null;
                                @endphp
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-16 h-16 rounded-2xl bg-slate-900 text-white flex items-center justify-center shrink-0 font-black text-xs overflow-hidden">
                                        @if($event && $event->banner)
                                        <img src="{{ Storage::url($event->banner) }}" alt="event Banner"
                                            class="w-full h-full object-cover">
                                        @else
                                        🎟️
                                        @endif
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <h4 class="font-extrabold text-slate-900 text-sm">
                                            {{ $event->title ?? 'Tiket event Budaya' }}
                                        </h4>
                                        <p class="text-xs text-slate-500">
                                            Tipe Tiket: <strong class="text-slate-800">{{ $item->ticket->name ?? 'Tiket
                                                Masuk' }}</strong>
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Jumlah: <strong class="text-slate-800">{{ $item->quantity }}x Tiket</strong>
                                            @ Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-black text-slate-900">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                                <div class="flex justify-between text-slate-500">
                                    <span>Subtotal Tiket</span>
                                    <strong class="text-slate-800">Rp {{ number_format($booking->total_amount, 0, ',',
                                        '.') }}</strong>
                                </div>
                                <div class="flex justify-between text-slate-500">
                                    <span>Biaya Layanan Admin</span>
                                    <strong class="text-emerald-600">Gratis</strong>
                                </div>
                                <div
                                    class="flex justify-between text-sm font-black text-slate-900 pt-2 border-t border-slate-100">
                                    <span>Total Yang Harus Dibayar</span>
                                    <span class="text-lg text-amber-600">Rp {{ number_format($booking->total_amount, 0,
                                        ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Instruksi Metode Pembayaran -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
                            <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-4">
                                Instruksi Pembayaran Transfer / QRIS
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Option 1: Transfer Bank -->
                                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="font-black text-xs text-blue-950 uppercase">Bank BCA</span>
                                        <span
                                            class="text-[10px] bg-blue-100 text-blue-900 font-bold px-2 py-0.5 rounded">Transfer</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase block">Nomor
                                            Rekening</span>
                                        <div class="flex items-center justify-between mt-1">
                                            <span
                                                class="font-black text-slate-900 text-sm tracking-wider">123-456-7890</span>
                                            <button
                                                onclick="navigator.clipboard.writeText('1234567890'); alert('Nomor Rekening BCA disalin!');"
                                                class="text-[10px] bg-white border border-slate-300 font-bold text-slate-700 px-2.5 py-1 rounded-lg hover:bg-slate-100 transition">
                                                Salin
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-[11px] text-slate-500">Atas Nama: <strong>PT CIREVA KARYA
                                            BUDAYA</strong></p>
                                </div>

                                <!-- Option 2: QRIS -->
                                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-3 text-center">
                                    <span class="font-black text-xs text-slate-900 uppercase block">Scan QRIS
                                        Cireva</span>
                                    <div
                                        class="w-28 h-28 bg-white border border-slate-300 rounded-xl mx-auto flex items-center justify-center p-2 shadow-sm">
                                        <svg class="w-full h-full text-slate-800" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-3 0h2v3h-2v-3zm3 3h3v5h-3v-5zm-3 2h2v3h-2v-3zm-2-2h2v2h-2v-2z" />
                                        </svg>
                                    </div>
                                    <p class="text-[10px] text-slate-500">Bisa di-scan menggunakan GoPay, OVO, Dana, BCA
                                        Mobile, dll.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN: FORM UPLOAD BUKTI PEMBAYARAN (1 COL) -->
                    <div class="space-y-6">

                        <!-- Box Upload Bukti Pembayaran -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-5">
                            <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3">
                                Upload Bukti Pembayaran
                            </h3>

                            @if(in_array($statusVal, ['paid', 'payment_completed', 'confirmed']))
                            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-center space-y-3">
                                <div
                                    class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto text-xl shadow-md">
                                    ✓
                                </div>
                                <div>
                                    <h4 class="font-black text-emerald-900 text-sm">Pembayaran Telah Diverifikasi</h4>
                                    <p class="text-xs text-emerald-700 mt-1">Selamat! Tiket event Anda telah diterbitkan
                                        & dapat diakses di menu E-Ticket.</p>
                                </div>
                                <a href="{{ route('user.tickets.index') }}"
                                    class="inline-block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-sm">
                                    Lihat E-Ticket Saya
                                </a>
                            </div>
                            @elseif(in_array($statusVal, ['cancelled', 'expired']))
                            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 text-center space-y-3">
                                <div
                                    class="w-12 h-12 bg-rose-500 text-white rounded-full flex items-center justify-center mx-auto text-xl shadow-md">
                                    ✕
                                </div>
                                <div>
                                    <h4 class="font-black text-rose-900 text-sm">Pemesanan Dibatalkan</h4>
                                    <p class="text-xs text-rose-700 mt-1">Pemesanan ini telah dibatalkan. Kuota tiket
                                        telah dikembalikan ke ketersediaan umum.</p>
                                </div>
                                <a href="{{ route('events.index') }}"
                                    class="inline-block w-full bg-blue-950 hover:bg-blue-900 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-sm">
                                    Pesan Tiket Lain
                                </a>
                            </div>
                            @elseif($hasProof)
                            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 text-center space-y-3">
                                <div
                                    class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto text-xl shadow-md">
                                    ⏳
                                </div>
                                <div>
                                    <h4 class="font-black text-blue-900 text-sm">Bukti Pembayaran Terkirim</h4>
                                    <p class="text-xs text-blue-700 mt-1">Bukti pembayaran Anda berhasil diunggah dan
                                        sedang diproses verifikasi oleh Admin.</p>
                                </div>
                            </div>

                            <!-- Re-upload / Update optional form -->
                            @if($booking->transaction)
                            <form action="{{ route('user.payments.upload', $booking->transaction) }}" method="POST"
                                enctype="multipart/form-data" class="space-y-4 pt-2">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Ulang Bukti
                                        Pembayaran (Opsional)</label>
                                    <input type="file" name="proof_file" accept="image/*,.pdf"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-700 focus:ring-2 focus:ring-blue-900">
                                </div>
                                <button type="submit"
                                    class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-sm">
                                    Update Bukti Pembayaran
                                </button>
                            </form>
                            @endif
                            @else
                            <!-- Upload Form for Pending Transaction -->
                            @if($booking->transaction)
                            <form action="{{ route('user.payments.upload', $booking->transaction) }}" method="POST"
                                enctype="multipart/form-data" class="space-y-4">
                                @csrf

                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-800">
                                        Pilih Berkas Bukti Pembayaran <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="file" name="proof_file" accept="image/*,.pdf" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-700 focus:ring-2 focus:ring-blue-900">
                                    <span class="text-[10px] text-slate-400 block">Format: JPG, PNG, WEBP, PDF (Maks.
                                        2MB)</span>
                                </div>

                                <button type="submit"
                                    class="w-full bg-blue-950 hover:bg-blue-900 text-white font-extrabold py-3.5 px-4 rounded-xl text-xs transition shadow-md flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span>Upload Bukti Pembayaran</span>
                                </button>
                            </form>
                            @endif

                            <div
                                class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs text-amber-900 flex items-start gap-2.5">
                                <span class="text-base">ℹ️</span>
                                <div>
                                    <strong class="font-bold block">Verifikasi Admin</strong>
                                    <p class="mt-0.5 text-[11px] leading-relaxed">Setelah bukti pembayaran diunggah,
                                        Admin Cireva akan memverifikasi pembayaran Anda max 1x24 jam.</p>
                                </div>
                            </div>
                            @endif

                            <!-- Cancel Booking Form -->
                            @if(in_array($statusVal, ['pending', 'waiting_payment']))
                            <div class="pt-4 border-t border-slate-100">
                                <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan tiket ini?')"
                                        class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold py-2.5 rounded-xl text-xs transition">
                                        Batalkan Pesanan Tiket
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>

            </main>
        </div>
    </div>
</x-app-layout>