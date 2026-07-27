<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Pesan Tiket event') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Component -->
                <x-user-sidebar />

                <!-- Main Content -->
                <div class="flex-1 space-y-6" x-data="{ 
                    quantity: {{ (int) request('quantity', 1) }},
                    unitPrice: {{ (float) ($selectedTicket->price ?? 0) }},
                    serviceFee: {{ ($selectedTicket->price ?? 0) > 0 ? 5000 : 0 }},
                    ticketId: {{ $selectedTicket->id ?? 0 }},
                    agreed: false,
                    selectedDate: '{{ $selectedDateFormatted }}',
                    paymentMethod: 'qris',
                    get subtotal() { return this.quantity * this.unitPrice; },
                    get total() { return this.subtotal + (this.unitPrice > 0 ? this.serviceFee : 0); }
                }">

                    <!-- Breadcrumbs -->
                    <nav class="flex text-xs text-slate-500 gap-2 items-center">
                        <a href="{{ route('user.dashboard') }}" class="hover:text-slate-900 font-medium">Beranda</a>
                        <span>&rsaquo;</span>
                        <a href="{{ route('events.index') }}" class="hover:text-slate-900 font-medium">Informasi
                            event</a>
                        <span>&rsaquo;</span>
                        <a href="{{ route('events.show', $event) }}" class="hover:text-slate-900 font-medium">{{
                            $event->title }}</a>
                        <span>&rsaquo;</span>
                        <span class="text-blue-950 font-bold">Pesan Tiket</span>
                    </nav>

                    <!-- Page Header Title -->
                    <div class="border-b border-gray-100 pb-2">
                        <h1 class="text-2xl font-black text-slate-900">Pesan Tiket</h1>
                        <p class="text-xs text-slate-500 mt-1">Lengkapi data pemesanan Anda untuk mendapatkan E-Ticket.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Column: Form Steps -->
                        <div class="lg:col-span-2 space-y-6">

                            <!-- event Hero Card Header -->
                            <div
                                class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-5 items-center">
                                @if($event->banner)
                                <img src="{{ Storage::url($event->banner) }}" alt="{{ $event->title }}"
                                    class="w-full sm:w-44 h-32 object-cover rounded-2xl shrink-0">
                                @else
                                <div
                                    class="w-full sm:w-44 h-32 bg-slate-900 rounded-2xl flex items-center justify-center text-slate-400 font-bold text-xs shrink-0">
                                    CIREVA event
                                </div>
                                @endif
                                <div class="space-y-2 text-xs">
                                    <span
                                        class="inline-block bg-amber-100 text-amber-900 font-extrabold px-3 py-1 rounded-full text-[10px] uppercase">
                                        {{ $event->category->name ?? 'Kebudayaan' }}
                                    </span>
                                    <h2 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $event->title }}
                                    </h2>
                                    <div class="flex items-center gap-2 text-slate-500 font-medium">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        <span>{{ $event->location->name ?? '-' }}, {{ $event->location->city ??
                                            'Cirebon' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-slate-500 font-medium">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>
                                            @php $sched = $event->schedules->first(); @endphp
                                            {{ $sched && $sched->start_datetime ?
                                            \Carbon\Carbon::parse($sched->start_datetime)->format('d M Y, H:i') . ' WIB'
                                            : 'Jadwal event Berlangsung' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Booking Form -->
                            <form action="{{ route('user.bookings.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="tickets[0][ticket_id]" :value="ticketId">
                                <input type="hidden" name="tickets[0][quantity]" :value="quantity">

                                <!-- Step 1: Pilih Tiket -->
                                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                                    <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                                        <span
                                            class="w-6 h-6 rounded-full bg-blue-950 text-white flex items-center justify-center text-xs">1</span>
                                        <span>Pilih Tiket</span>
                                    </h3>

                                    @foreach($event->tickets as $t)
                                    @php
                                    $avail = max(0, $t->quota - $t->sold);
                                    @endphp
                                    <div class="border rounded-2xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition"
                                        :class="ticketId == {{ $t->id }} ? 'border-amber-500 bg-amber-50/20' : 'border-gray-200 bg-white'">
                                        <div class="flex items-start gap-3">
                                            <input type="radio" name="selected_ticket_radio" value="{{ $t->id }}"
                                                @click="ticketId = {{ $t->id }}; unitPrice = {{ $t->price }}; serviceFee = {{ $t->price > 0 ? 5000 : 0 }};"
                                                :checked="ticketId == {{ $t->id }}"
                                                class="mt-1 text-amber-600 focus:ring-amber-500">
                                            <div>
                                                <h4 class="font-extrabold text-slate-900 text-xs">{{ $t->name }}</h4>
                                                <p class="text-slate-500 text-[11px] mt-0.5">{{ $t->description ??
                                                    'Akses masuk venue pertunjukan' }}</p>
                                                <p class="text-amber-700 font-black text-sm mt-1">
                                                    {{ $t->price == 0 ? 'Gratis (Free Entry)' : 'Rp ' .
                                                    number_format($t->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0">
                                            <button type="button" @click="if (quantity > 1) quantity--"
                                                class="w-8 h-8 rounded-xl border border-gray-300 flex items-center justify-center font-bold text-slate-700 hover:bg-slate-100 transition text-sm">–</button>
                                            <input type="number" x-model="quantity" min="1" max="{{ min(10, $avail) }}"
                                                readonly
                                                class="w-12 text-center border-0 bg-transparent font-extrabold text-xs text-slate-900">
                                            <button type="button"
                                                @click="if (quantity < {{ min(10, $avail) }}) quantity++"
                                                class="w-8 h-8 rounded-xl border border-gray-300 flex items-center justify-center font-bold text-slate-700 hover:bg-slate-100 transition text-sm">+</button>
                                        </div>
                                    </div>
                                    @endforeach

                                    <div
                                        class="bg-blue-50/60 border border-blue-100 text-blue-900 p-3.5 rounded-2xl text-xs flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-700 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Anak di bawah usia 5 tahun tidak dikenakan biaya tiket masuk.</span>
                                    </div>
                                </div>

                                <!-- Step 2: Data Pemesan -->
                                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                                    <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                                        <span
                                            class="w-6 h-6 rounded-full bg-blue-950 text-white flex items-center justify-center text-xs">2</span>
                                        <span>Data Pemesan</span>
                                    </h3>
                                    <p class="text-xs text-slate-500">Data pemesan akan digunakan untuk verifikasi dan
                                        pengiriman E-Ticket ke akun Anda.</p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                        <div>
                                            <x-input-label value="Nama Lengkap *"
                                                class="font-bold text-xs text-slate-700 mb-1" />
                                            <x-text-input name="name" type="text" :value="auth()->user()->name" required
                                                class="w-full text-xs rounded-xl" />
                                        </div>
                                        <div>
                                            <x-input-label value="Email *"
                                                class="font-bold text-xs text-slate-700 mb-1" />
                                            <x-text-input name="email" type="email" :value="auth()->user()->email"
                                                required class="w-full text-xs rounded-xl" />
                                        </div>
                                        <div>
                                            <x-input-label value="Nomor Telepon *"
                                                class="font-bold text-xs text-slate-700 mb-1" />
                                            <x-text-input name="phone" type="text" value="082312345678" required
                                                class="w-full text-xs rounded-xl" placeholder="Contoh: 08123456789" />
                                        </div>
                                        <div>
                                            <x-input-label value="Jenis Identitas *"
                                                class="font-bold text-xs text-slate-700 mb-1" />
                                            <select name="identity_type"
                                                class="w-full text-xs rounded-xl border-gray-300 focus:border-amber-500 focus:ring-amber-500">
                                                <option value="KTP" selected>KTP (Kartu Tanda Penduduk)</option>
                                                <option value="SIM">SIM</option>
                                                <option value="Paspor">Paspor</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-2">
                                            <x-input-label value="Nomor Identitas (NIK) *"
                                                class="font-bold text-xs text-slate-700 mb-1" />
                                            <x-text-input name="identity_number" type="text" value="3275020409980001"
                                                required class="w-full text-xs rounded-xl"
                                                placeholder="Masukkan 16 Digit NIK KTP" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Metode Pembayaran -->
                                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                                    <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                                        <span
                                            class="w-6 h-6 rounded-full bg-blue-950 text-white flex items-center justify-center text-xs">3</span>
                                        <span>Metode Pembayaran</span>
                                    </h3>

                                    <div class="space-y-3">
                                        <label
                                            class="border rounded-2xl p-4 flex items-center justify-between cursor-pointer transition"
                                            :class="paymentMethod === 'qris' ? 'border-blue-950 bg-blue-50/20' : 'border-gray-200'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="payment_method" value="qris"
                                                    x-model="paymentMethod" class="text-blue-950 focus:ring-blue-900">
                                                <div>
                                                    <div class="font-extrabold text-xs text-slate-900">QRIS Instant
                                                        Payment</div>
                                                    <div class="text-slate-500 text-[11px]">Bayar cepat menggunakan QRIS
                                                        BCA, GoPay, OVO, Dana</div>
                                                </div>
                                            </div>
                                            <span
                                                class="font-black text-xs text-blue-950 bg-blue-100 px-2.5 py-1 rounded-lg">QRIS</span>
                                        </label>

                                        <label
                                            class="border rounded-2xl p-4 flex items-center justify-between cursor-pointer transition"
                                            :class="paymentMethod === 'bank' ? 'border-blue-950 bg-blue-50/20' : 'border-gray-200'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="payment_method" value="bank"
                                                    x-model="paymentMethod" class="text-blue-950 focus:ring-blue-900">
                                                <div>
                                                    <div class="font-extrabold text-xs text-slate-900">Transfer Bank &
                                                        E-Wallet</div>
                                                    <div class="text-slate-500 text-[11px]">Transfer ke rekening resmi
                                                        CIREVA</div>
                                                </div>
                                            </div>
                                            <span
                                                class="font-black text-xs text-slate-700 bg-gray-100 px-2.5 py-1 rounded-lg">BANK
                                                TRANSFER</span>
                                        </label>
                                    </div>

                                    <div
                                        class="bg-amber-50/70 border border-amber-200/80 text-amber-900 p-3.5 rounded-2xl text-xs flex items-center gap-2 font-bold">
                                        <svg class="w-4 h-4 text-amber-700 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <span>Pembayaran diamankan dengan sistem enkripsi tingkat tinggi CIREVA.</span>
                                    </div>

                                    <!-- Terms Agreement & Submit -->
                                    <div class="pt-4 border-t border-gray-100 space-y-4">
                                        <label class="flex items-center gap-2.5 cursor-pointer">
                                            <input type="checkbox" x-model="agreed"
                                                class="rounded text-blue-950 focus:ring-blue-900">
                                            <span class="text-xs font-semibold text-slate-700">Saya telah membaca dan
                                                menyetujui <a href="#" class="text-blue-900 font-bold underline">Syarat
                                                    & Ketentuan</a> pemesanan tiket.</span>
                                        </label>

                                        <button type="submit" :disabled="!agreed"
                                            :class="agreed ? 'bg-blue-950 hover:bg-blue-900 text-white shadow-md' : 'bg-slate-300 text-slate-500 cursor-not-allowed'"
                                            class="w-full py-3.5 rounded-2xl font-extrabold text-xs transition flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            <span>Lanjutkan ke Pembayaran</span>
                                        </button>
                                        <p class="text-center text-[11px] text-slate-400 font-medium">Data Anda aman dan
                                            terenkripsi secara otomatis.</p>
                                    </div>
                                </div>
                            </form>

                        </div>

                        <!-- Right Column: Summary & Info -->
                        <div class="space-y-6">

                            <!-- Ringkasan Pesanan Card -->
                            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                                <h3 class="font-extrabold text-sm text-slate-900 border-b border-gray-100 pb-3">
                                    Ringkasan Pesanan</h3>

                                <div class="flex gap-3 items-center">
                                    @if($event->banner)
                                    <img src="{{ Storage::url($event->banner) }}" alt="{{ $event->title }}"
                                        class="w-14 h-14 object-cover rounded-xl shrink-0">
                                    @else
                                    <div
                                        class="w-14 h-14 bg-slate-900 rounded-xl flex items-center justify-center text-slate-400 font-bold text-[10px] shrink-0">
                                        event</div>
                                    @endif
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs line-clamp-1">{{ $event->title }}
                                        </h4>
                                        <p class="text-slate-500 text-[11px] mt-0.5">{{ $event->location->name ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="border-t border-b border-gray-100 py-3 space-y-2 text-xs">
                                    <div class="flex justify-between text-slate-600">
                                        <span>Detail Tiket</span>
                                        <strong class="text-slate-900"
                                            x-text="unitPrice > 0 ? ('Rp ' + unitPrice.toLocaleString('id-ID') + ' x ' + quantity) : 'Gratis x ' + quantity"></strong>
                                    </div>
                                    <div class="flex justify-between text-slate-600">
                                        <span>Subtotal Tiket</span>
                                        <strong class="text-slate-900"
                                            x-text="'Rp ' + subtotal.toLocaleString('id-ID')"></strong>
                                    </div>
                                    <div class="flex justify-between text-slate-600">
                                        <span>Biaya Layanan</span>
                                        <strong class="text-slate-900"
                                            x-text="unitPrice > 0 ? ('Rp ' + serviceFee.toLocaleString('id-ID')) : 'Rp 0'"></strong>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center pt-1">
                                    <span class="font-extrabold text-xs text-slate-900 uppercase">Total
                                        Pembayaran</span>
                                    <span class="font-black text-lg text-amber-600"
                                        x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                                </div>

                                <div
                                    class="pt-3 space-y-2 text-[11px] text-slate-500 font-medium border-t border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <span class="text-emerald-600 font-bold">✓</span>
                                        <span>E-ticket langsung dikirim ke sistem & email.</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-emerald-600 font-bold">✓</span>
                                        <span>Dapat dibatalkan sesuai ketentuan.</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-emerald-600 font-bold">✓</span>
                                        <span>Transaksi 100% aman & terpercaya.</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Penting Card -->
                            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-3">
                                <h3 class="font-extrabold text-sm text-slate-900 border-b border-gray-100 pb-3">
                                    Informasi Penting</h3>
                                <ul class="space-y-2.5 text-xs text-slate-600">
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-blue-900 shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>E-ticket akan dikirim ke akun Anda setelah pembayaran diverifikasi.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-blue-900 shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Pastikan data nama & NIK yang Anda masukkan sesuai identitas asli.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-blue-900 shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span>Tiket bersifat pribadi dan tidak dapat dipindahtangankan tanpa
                                            izin.</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Butuh Bantuan Card -->
                            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-3">
                                <h3 class="font-extrabold text-sm text-slate-900 border-b border-gray-100 pb-3">Butuh
                                    Bantuan?</h3>
                                <div class="space-y-3 text-xs">
                                    <div class="flex items-center gap-3 text-slate-700 font-bold">
                                        <div
                                            class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-slate-900 font-extrabold">0823 1234 5678</div>
                                            <div class="text-slate-400 text-[10px]">WhatsApp Support</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-700 font-bold">
                                        <div
                                            class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-slate-900 font-extrabold">support@cireva.id</div>
                                            <div class="text-slate-400 text-[10px]">Email Customer Service</div>
                                        </div>
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