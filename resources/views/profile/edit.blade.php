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
                                class="font-bold text-slate-900">{{ $user->name }}</span>!</span>
                        <div
                            class="w-9 h-9 rounded-full bg-[#0096C7] text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Body -->
            <main class="p-8 flex-1 space-y-6">

                <!-- Breadcrumbs & Header Title -->
                <div class="space-y-1">
                    <nav class="flex text-xs text-slate-400 gap-2 items-center">
                        <a href="{{ route('user.dashboard') }}" class="hover:text-slate-800">Beranda</a>
                        <span>&rsaquo;</span>
                        <span class="font-bold text-slate-800">Profil Saya</span>
                    </nav>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profil Saya</h1>
                    <p class="text-xs text-slate-500">Kelola informasi profil dan lihat riwayat transaksi Anda.</p>
                </div>

                <!-- Tabs Row -->
                <div class="flex items-center gap-6 border-b border-slate-200 text-xs font-bold pb-2">
                    <button class="text-blue-950 border-b-2 border-blue-950 pb-2 -mb-2.5 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Edit Profil</span>
                    </button>
                </div>

                @if(session('status') === 'profile-updated')
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                    <span>✅</span>
                    <span>Informasi profil berhasil diperbarui!</span>
                </div>
                @endif

                <!-- MAIN CONTENT CARD -->
                <div class="max-w-4xl bg-white rounded-3xl p-8 border border-slate-100 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">Informasi Pribadi</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Perbarui nama dan alamat email akun Anda.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <!-- Profile Avatar Row -->
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-full bg-[#0096C7] text-white flex items-center justify-center font-black text-xl uppercase shadow-sm ring-4 ring-slate-100">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">{{ $user->name }}</h4>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                            <div class="space-y-1.5">
                                <label class="font-bold text-slate-700 block">Nama Lengkap <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-950 focus:bg-white transition">
                                @error('name')
                                <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-bold text-slate-700 block">Alamat Email <span
                                        class="text-rose-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-950 focus:bg-white transition">
                                @error('email')
                                <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit"
                                class="bg-blue-950 hover:bg-blue-900 text-white font-extrabold px-8 py-3 rounded-xl text-xs transition shadow-md">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                @if(session('status') === 'password-updated')
                <div
                    class="max-w-4xl p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                    <span>🔑</span>
                    <span>Password akun Anda berhasil diperbarui!</span>
                </div>
                @endif

                <!-- RESET PASSWORD CARD -->
                <div class="max-w-4xl bg-white rounded-3xl p-8 border border-slate-100 shadow-sm space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="font-extrabold text-slate-900 text-base">Ubah / Reset Password</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pastikan akun Anda menggunakan kata sandi yang kuat dan aman.</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        @method('put')

                        <div class="space-y-4 text-xs">
                            <div class="space-y-1.5">
                                <label class="font-bold text-slate-700 block">Password Saat Ini <span
                                        class="text-rose-500">*</span></label>
                                <input type="password" name="current_password" required autocomplete="current-password"
                                    placeholder="Masukkan password lama"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-950 focus:bg-white transition">
                                @error('current_password', 'updatePassword')
                                <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="font-bold text-slate-700 block">Password Baru <span
                                            class="text-rose-500">*</span></label>
                                    <input type="password" name="password" required autocomplete="new-password"
                                        placeholder="Minimal 8 karakter"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-950 focus:bg-white transition">
                                    @error('password', 'updatePassword')
                                    <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-1.5">
                                    <label class="font-bold text-slate-700 block">Konfirmasi Password Baru <span
                                            class="text-rose-500">*</span></label>
                                    <input type="password" name="password_confirmation" required autocomplete="new-password"
                                        placeholder="Ulangi password baru"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-950 focus:bg-white transition">
                                    @error('password_confirmation', 'updatePassword')
                                    <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit"
                                class="bg-amber-600 hover:bg-amber-500 text-white font-extrabold px-8 py-3 rounded-xl text-xs transition shadow-md">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>