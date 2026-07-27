<x-guest-layout>
    <div class="bg-white rounded-3xl p-8 shadow-2xl space-y-6" x-data="{ showPassword: false }">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center justify-center">
                <x-application-logo class="h-12 w-auto text-amber-600 fill-current" />
                <span class="font-extrabold text-2xl tracking-wider text-slate-900 mt-1">CIREVA</span>
            </a>
            <p class="text-[11px] font-bold text-gray-400 tracking-widest uppercase">
                CULTURAL eventS & CONNECTIONS
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email or Username Input -->
            <div>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                        placeholder="Email atau Username"
                        class="w-full pl-11 pr-4 py-3 bg-gray-100 border-none rounded-xl text-sm placeholder-gray-400 text-gray-800 focus:bg-white focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password Input -->
            <div>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                        placeholder="Kata Sandi"
                        class="w-full pl-11 pr-11 py-3 bg-gray-100 border-none rounded-xl text-sm placeholder-gray-400 text-gray-800 focus:bg-white focus:ring-2 focus:ring-amber-500 transition">
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 013.985-.863c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-6.165-4.417a3 3 0 01-4.243-4.243m4.243 4.243L3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-xs text-gray-500 pt-1">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember"
                        class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                    <span class="ms-2">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="hover:text-gray-800 transition">
                    Lupa Kata Sandi?
                </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-[#C2974F] hover:bg-[#b0853e] text-white font-bold py-3.5 px-4 rounded-full text-sm shadow-md transition">
                    Masuk Sekarang
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="relative flex items-center justify-center my-4">
            <div class="border-t border-gray-200 w-full"></div>
            <span class="bg-white px-3 text-xs text-gray-400 font-medium shrink-0">Atau gunakan</span>
            <div class="border-t border-gray-200 w-full"></div>
        </div>

        <!-- Social Buttons -->
        <div class="grid grid-cols-2 gap-3">
            <button type="button"
                class="flex items-center justify-center gap-2 py-2.5 px-4 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4"
                        d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z" />
                    <path fill="#34A853"
                        d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.11-6.72-4.96H1.29v3.15C3.26 21.3 7.31 24 12 24z" />
                    <path fill="#FBBC05"
                        d="M5.28 14.24c-.25-.72-.38-1.49-.38-2.24s.13-1.52.38-2.24V6.61H1.29C.47 8.24 0 10.06 0 12s.47 3.76 1.29 5.39l3.99-3.15z" />
                    <path fill="#EA4335"
                        d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.61l3.99 3.15c.95-2.85 3.6-4.96 6.72-4.96z" />
                </svg>
                <span>Google</span>
            </button>

            <button type="button"
                class="flex items-center justify-center gap-2 py-2.5 px-4 bg-[#1877F2] hover:bg-[#166fe5] rounded-xl text-xs font-semibold text-white transition shadow-sm">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path
                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
                <span>Facebook</span>
            </button>
        </div>

        <!-- Footer Register Link -->
        <div class="text-center text-xs text-gray-500 pt-2">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-bold text-gray-800 hover:underline">
                Daftar sekarang
            </a>
        </div>
    </div>
</x-guest-layout>