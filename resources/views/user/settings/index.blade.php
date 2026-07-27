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
                        class="w-full bg-slate-50 text-xs border-0 rounded-full py-2.5 pl-5 pr-10 focus:ring-2 focus:ring-blue-950 focus:bg-white transition placeholder-slate-400">
                    <svg class="w-4 h-4 text-slate-400 absolute right-4 top-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('notifications.index') }}"
                        class="relative p-2 text-slate-500 hover:text-slate-700 rounded-full hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            class="absolute top-1 right-1 bg-rose-500 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full">2</span>
                    </a>

                    <div class="flex items-center gap-3 pl-2 border-l border-slate-100">
                        <span class="text-xs font-semibold text-slate-700">Halo, <span
                                class="font-bold text-slate-900">{{ Auth::user()->name }}</span>!</span>
                        <div
                            class="w-9 h-9 rounded-full bg-blue-950 text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Body Content -->
            <main class="p-8 flex-1 space-y-6">

                <!-- Title & Breadcrumbs -->
                <div class="space-y-1">
                    <nav class="flex text-xs text-slate-400 gap-2 items-center">
                        <a href="{{ route('user.dashboard') }}" class="hover:text-slate-800">Beranda</a>
                        <span>&rsaquo;</span>
                        <span class="font-bold text-slate-800">Pengaturan Akun</span>
                    </nav>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Akun</h1>
                    <p class="text-xs text-slate-500">Kelola keamanan kata sandi, preferensi notifikasi, dan privasi
                        akun Anda.</p>
                </div>

                @if (session('status') === 'password-updated')
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                    <span>✅</span>
                    <span>Kata sandi Anda berhasil diperbarui!</span>
                </div>
                @endif

                <!-- MAIN GRID: LEFT (2 COLS) | RIGHT (1 COL) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- LEFT COLUMN (2 COLS) -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- 1. Form Fitur Ganti Password -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
                            <div class="border-b border-slate-100 pb-4">
                                <h3 class="font-extrabold text-slate-900 text-base">Ganti Kata Sandi (Password)</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Pastikan akun Anda menggunakan kata sandi yang
                                    panjang dan acak untuk menjaga keamanan.</p>
                            </div>

                            <form method="POST" action="{{ route('password.update') }}" class="space-y-4 max-w-xl">
                                @csrf
                                @method('put')

                                <div class="space-y-1 text-xs">
                                    <label for="update_password_current_password" class="font-bold text-slate-700">Kata
                                        Sandi Saat Ini</label>
                                    <input id="update_password_current_password" name="current_password" type="password"
                                        autocomplete="current-password" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-950">
                                    @if($errors->updatePassword->get('current_password'))
                                    <p class="text-xs text-rose-600 mt-1">{{
                                        $errors->updatePassword->first('current_password') }}</p>
                                    @endif
                                </div>

                                <div class="space-y-1 text-xs">
                                    <label for="update_password_password" class="font-bold text-slate-700">Kata Sandi
                                        Baru</label>
                                    <input id="update_password_password" name="password" type="password"
                                        autocomplete="new-password" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-950">
                                    @if($errors->updatePassword->get('password'))
                                    <p class="text-xs text-rose-600 mt-1">{{ $errors->updatePassword->first('password')
                                        }}</p>
                                    @endif
                                </div>

                                <div class="space-y-1 text-xs">
                                    <label for="update_password_password_confirmation"
                                        class="font-bold text-slate-700">Konfirmasi Kata Sandi Baru</label>
                                    <input id="update_password_password_confirmation" name="password_confirmation"
                                        type="password" autocomplete="new-password" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-950">
                                    @if($errors->updatePassword->get('password_confirmation'))
                                    <p class="text-xs text-rose-600 mt-1">{{
                                        $errors->updatePassword->first('password_confirmation') }}</p>
                                    @endif
                                </div>

                                <div class="pt-2">
                                    <button type="submit"
                                        class="bg-blue-950 hover:bg-blue-900 text-white font-extrabold px-6 py-2.5 rounded-xl text-xs transition shadow-sm">
                                        Perbarui Kata Sandi
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- 2. Preferensi Notifikasi & Komunikasi -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
                            <div class="border-b border-slate-100 pb-4">
                                <h3 class="font-extrabold text-slate-900 text-base">Preferensi Notifikasi</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Atur pengiriman notifikasi pengingat event dan
                                    status transaksi ke email Anda.</p>
                            </div>

                            <div class="space-y-4 text-xs">
                                <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                    <div>
                                        <strong class="font-bold text-slate-900 block">Notifikasi event Baru &
                                            Rekomendasi</strong>
                                        <span class="text-slate-400 text-[11px]">Terima info pagelaran seni dan festival
                                            budaya terbaru di Cirebon.</span>
                                    </div>
                                    <input type="checkbox" checked
                                        class="rounded border-slate-300 text-blue-950 focus:ring-blue-950 w-4 h-4">
                                </div>

                                <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                    <div>
                                        <strong class="font-bold text-slate-900 block">Pengingat Jadwal event
                                            (H-1)</strong>
                                        <span class="text-slate-400 text-[11px]">Terima pengingat email 24 jam sebelum
                                            event berlangsung.</span>
                                    </div>
                                    <input type="checkbox" checked
                                        class="rounded border-slate-300 text-blue-950 focus:ring-blue-950 w-4 h-4">
                                </div>

                                <div class="flex items-center justify-between py-2">
                                    <div>
                                        <strong class="font-bold text-slate-900 block">Notifikasi Status Pembayaran &
                                            Tiket</strong>
                                        <span class="text-slate-400 text-[11px]">Terima bukti pembayaran & penerbitan
                                            e-tiket secara instant.</span>
                                    </div>
                                    <input type="checkbox" checked
                                        class="rounded border-slate-300 text-blue-950 focus:ring-blue-950 w-4 h-4">
                                </div>
                            </div>
                        </div>

                        <!-- 3. Zona Bahaya / Danger Zone (Hapus Akun) -->
                        <div class="bg-rose-50 border border-rose-200 rounded-3xl p-6 space-y-4"
                            x-data="{ showDeleteModal: false }">
                            <div>
                                <h3 class="font-extrabold text-rose-900 text-base">Hapus Akun Pengguna</h3>
                                <p class="text-xs text-rose-700 mt-0.5">Setelah akun Anda dihapus, semua data pesanan,
                                    e-tiket, dan informasi Anda akan dihapus secara permanen.</p>
                            </div>

                            <button type="button" @click="showDeleteModal = true"
                                class="bg-rose-600 hover:bg-rose-700 text-white font-extrabold px-5 py-2.5 rounded-xl text-xs transition shadow-sm">
                                Hapus Akun Saya
                            </button>

                            <!-- Delete Modal -->
                            <div x-show="showDeleteModal"
                                class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 text-left"
                                x-cloak>
                                <div class="bg-white rounded-3xl p-6 max-w-md w-full space-y-4 shadow-2xl"
                                    @click.outside="showDeleteModal = false">
                                    <h4 class="font-black text-slate-900 text-base">Konfirmasi Hapus Akun</h4>
                                    <p class="text-xs text-slate-500">Masukkan password Anda untuk mengonfirmasi bahwa
                                        Anda ingin menghapus akun secara permanen.</p>

                                    <form method="post" action="{{ route('user.profile.destroy') }}" class="space-y-4">
                                        @csrf
                                        @method('delete')

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi
                                                Anda</label>
                                            <input type="password" name="password" required
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-rose-500"
                                                placeholder="Masukkan password untuk konfirmasi">
                                        </div>

                                        <div class="flex justify-end gap-2 pt-2">
                                            <button type="button" @click="showDeleteModal = false"
                                                class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2 rounded-xl text-xs">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-4 py-2 rounded-xl text-xs">
                                                Ya, Hapus Permanen
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN (1 COL) -->
                    <div class="space-y-6">

                        <!-- Keamanan & Sesi Box -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                            <div class="border-b border-slate-100 pb-3">
                                <h3 class="font-extrabold text-slate-900 text-sm">Status Keamanan</h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">Informasi sesi dan otentikasi akun.</p>
                            </div>

                            <div class="space-y-3.5 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Email Utama</span>
                                    <strong class="text-slate-900 truncate max-w-[150px]">{{ Auth::user()->email
                                        }}</strong>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Status Email</span>
                                    <span
                                        class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded">Terverifikasi</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Sesi Aktif</span>
                                    <span class="text-slate-800 font-bold">1 Perangkat Aktif</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pusat Bantuan Box -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                            <div class="border-b border-slate-100 pb-3">
                                <h3 class="font-extrabold text-slate-900 text-sm">Bantuan & Keamanan</h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">Pertanyaan seputar kata sandi atau akun?
                                </p>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed">
                                Jika Anda mengalami masalah saat memperbarui kata sandi atau mendeteksi aktivitas
                                mencurigakan, hubungi tim keamanan Cireva.
                            </p>

                            <a href="mailto:support@cireva.id"
                                class="inline-flex items-center gap-2 text-xs font-bold text-blue-950 hover:underline">
                                <span>Hubungi Security Support &rarr;</span>
                            </a>
                        </div>

                    </div>

                </div>

            </main>
        </div>
    </div>
</x-app-layout>