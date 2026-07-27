<x-public-layout>
    <div
        class="py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-50/30 via-white to-amber-50/40 min-h-[calc(100vh-5rem)] flex items-center justify-center">
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 grid grid-cols-1 lg:grid-cols-12"
            x-data="{ role: '{{ old('role', 'user') }}', showPass: false, showConfirmPass: false }">

            <!-- Left Branding Banner (5 Cols) -->
            <div
                class="lg:col-span-5 bg-gradient-to-b from-[#182335] via-[#101A29] to-[#0A121E] text-white p-8 lg:p-12 flex flex-col justify-between relative overflow-hidden min-h-[400px]">
                <!-- Decorative Glow -->
                <div class="absolute -top-24 -left-24 w-60 h-60 bg-amber-500/10 rounded-full blur-3xl"></div>

                <!-- Logo & Artwork -->
                <div class="relative z-10 my-auto text-center space-y-4 py-8">
                    <x-application-logo class="h-28 w-auto text-amber-500 fill-current mx-auto filter drop-shadow-lg" />
                    <h2 class="text-3xl font-extrabold tracking-widest text-amber-400 font-serif">CIREVA</h2>
                    <p class="text-[10px] tracking-[0.25em] text-slate-400 font-bold uppercase">
                        CULTURE • eventS • CONNECTIONS
                    </p>
                </div>

                <!-- Bottom Text -->
                <div class="relative z-10 space-y-2">
                    <h3 class="text-lg font-bold text-white">Gerbang Budaya Menanti Anda.</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-normal">
                        Bergabunglah dengan komunitas CIREVA untuk mendapatkan akses eksklusif ke event budaya terbaik
                        di Cirebon.
                    </p>
                </div>
            </div>

            <!-- Right Form Section (7 Cols) -->
            <div class="lg:col-span-7 p-8 sm:p-10 space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h2>
                    <p class="text-xs text-slate-500 mt-1">Lengkapi data diri Anda untuk memulai perjalanan budaya.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Role Selection -->
                    <div>
                        <x-input-label value="Daftar Sebagai" class="mb-2 text-xs font-semibold text-gray-700" />
                        <div class="grid grid-cols-2 gap-3">
                            <label
                                :class="role === 'user' ? 'border-amber-600 bg-amber-50/60 text-amber-950 ring-2 ring-amber-600' : 'border-gray-200 hover:border-gray-300'"
                                class="flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition text-center">
                                <input type="radio" name="role" value="user" x-model="role" class="sr-only">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-bold text-xs">Visitor</span>
                            </label>

                            <label
                                :class="role === 'organizer' ? 'border-amber-600 bg-amber-50/60 text-amber-950 ring-2 ring-amber-600' : 'border-gray-200 hover:border-gray-300'"
                                class="flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition text-center">
                                <input type="radio" name="role" value="organizer" x-model="role" class="sr-only">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="font-bold text-xs">Organizer</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-1" />
                    </div>

                    <!-- Full Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')"
                            class="text-xs font-semibold text-gray-700 mb-1" />
                        <div class="relative flex items-center">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="name" type="text" name="name" :value="old('name')" required autofocus
                                placeholder="Masukkan nama lengkap"
                                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <!-- Email & Phone (Grid 2 cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')"
                                class="text-xs font-semibold text-gray-700 mb-1" />
                            <div class="relative flex items-center">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" :value="old('email')" required
                                    placeholder="email@contoh.com"
                                    class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <x-input-label for="phone" :value="__('Nomor Telepon')"
                                class="text-xs font-semibold text-gray-700 mb-1" />
                            <div class="relative flex items-center">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input id="phone" type="text" name="phone" :value="old('phone')" placeholder="0812..."
                                    class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Kata Sandi')"
                            class="text-xs font-semibold text-gray-700 mb-1" />
                        <div class="relative flex items-center">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" :type="showPass ? 'text' : 'password'" name="password" required
                                placeholder="Minimal 8 karakter"
                                class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition">
                            <button type="button" @click="showPass = !showPass"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 013.985-.863c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-6.165-4.417a3 3 0 01-4.243-4.243m4.243 4.243L3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')"
                            class="text-xs font-semibold text-gray-700 mb-1" />
                        <div class="relative flex items-center">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <input id="password_confirmation" :type="showConfirmPass ? 'text' : 'password'"
                                name="password_confirmation" required placeholder="Ulangi kata sandi"
                                class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition">
                            <button type="button" @click="showConfirmPass = !showConfirmPass"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                                <svg x-show="!showConfirmPass" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirmPass" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 013.985-.863c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-6.165-4.417a3 3 0 01-4.243-4.243m4.243 4.243L3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <!-- Password Hints Box -->
                    <div
                        class="bg-blue-50/70 border border-blue-100 rounded-xl p-3 text-[11px] text-slate-600 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full border border-slate-400 inline-block"></span>
                            <span>Minimal 8 karakter</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full border border-slate-400 inline-block"></span>
                            <span>Mengandung angka & simbol (@#$%^&*)</span>
                        </div>
                    </div>

                    <!-- Organizer SPK 15% Agreement Box -->
                    <div x-show="role === 'organizer'" x-transition
                        class="p-3 bg-amber-50 border border-amber-200 rounded-xl space-y-2">
                        <div class="text-[11px] text-amber-900 leading-relaxed">
                            <span class="font-bold">Ketentuan Perjanjian Kerjasama (SPK) Organizer:</span>
                            <p class="mt-0.5">
                                Menyetujui pembagian komisi / biaya layanan platform sebesar <span
                                    class="font-bold underline">15% dari hasil penjualan tiket</span>.
                            </p>
                        </div>
                        <label class="flex items-start gap-2 cursor-pointer pt-1 border-t border-amber-200/80">
                            <input type="checkbox" name="spk_agreement" value="1" :required="role === 'organizer'"
                                class="mt-0.5 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-[11px] font-semibold text-gray-800">
                                Saya menyetujui Persyaratan SPK (Potongan 15%).
                            </span>
                        </label>
                        <x-input-error :messages="$errors->get('spk_agreement')" class="mt-1" />
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="pt-1">
                        <label class="flex items-start gap-2 cursor-pointer">
                            <input type="checkbox" required
                                class="mt-0.5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-xs text-gray-600 leading-normal">
                                Saya setuju dengan <a href="#" class="font-bold text-amber-700 hover:underline">Syarat &
                                    Ketentuan</a> serta <a href="#"
                                    class="font-bold text-amber-700 hover:underline">Kebijakan Privasi</a> yang berlaku
                                di platform CIREVA.
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-[#825608] hover:bg-[#6c4705] text-white font-bold py-3 px-6 rounded-xl text-sm flex items-center justify-center gap-2 transition shadow-md">
                            <span>Daftar Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Divider & Footer Link -->
                <div class="pt-4 border-t border-gray-100 text-center text-xs text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-slate-900 hover:underline ms-1">
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
</x-public-layout>