<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CIREVA') }} - Cultural events & Experience</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50 flex flex-col min-h-screen">
    <!-- Header / Navbar -->
    <header class="bg-white sticky top-0 z-50 border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <x-application-logo class="h-9 w-auto text-amber-600 fill-current" />
                        <span class="font-bold text-2xl tracking-wider text-slate-900">CIREVA</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2 text-sm font-medium text-gray-600">
                    <a href="{{ url('/') }}"
                        class="{{ request()->is('/') ? 'bg-amber-50 text-amber-700 font-semibold px-4 py-2 rounded-full' : 'hover:text-amber-600 px-3 py-2 transition' }}">
                        Beranda
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="{{ request()->routeIs('events.*') ? 'bg-amber-50 text-amber-700 font-semibold px-4 py-2 rounded-full' : 'hover:text-amber-600 px-3 py-2 transition' }}">
                        Event
                    </a>
                    <a href="{{ route('calendar.index') }}"
                        class="{{ request()->routeIs('calendar.*') ? 'bg-amber-50 text-amber-700 font-semibold px-4 py-2 rounded-full' : 'hover:text-amber-600 px-3 py-2 transition' }}">
                        Kalender Budaya
                    </a>
                    @auth
                    <a href="{{ route('user.tickets.index') }}"
                        class="{{ request()->routeIs('user.tickets.*') ? 'bg-amber-50 text-amber-700 font-semibold px-4 py-2 rounded-full' : 'hover:text-amber-600 px-3 py-2 transition' }}">Tiket
                        Saya</a>
                    @endauth
                    <a href="{{ route('about') }}"
                        class="{{ request()->routeIs('about') ? 'bg-amber-50 text-amber-700 font-semibold px-4 py-2 rounded-full' : 'hover:text-amber-600 px-3 py-2 transition' }}">Tentang</a>
                </nav>

                <!-- User / Auth Actions -->
                <div class="flex items-center gap-3">
                    @auth
                    <a href="{{ route('dashboard') }}"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="px-5 py-2 rounded-full text-sm font-medium border border-gray-300 text-gray-800 hover:border-gray-400 hover:bg-gray-50 transition">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="px-5 py-2 rounded-full text-sm font-medium bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm">
                        Daftar
                    </a>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-[#070E1E] text-white pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-10 pb-12 border-b border-slate-800/80">
                <!-- Brand Column (2 cols) -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-2">
                        <x-application-logo class="h-8 w-auto text-amber-500 fill-current" />
                        <span class="font-bold text-2xl tracking-wider text-white">CIREVA</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-sm">
                        Connecting the world to the hidden cultural treasures of Cirebon. Experience authenticity,
                        heritage, and modern convenience in one gateway.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold tracking-widest text-slate-300 uppercase">QUICK LINKS</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-amber-400 transition">event Terkini</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition">Kalender Budaya</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition">Destinasi Populer</a></li>
                    </ul>
                </div>

                <!-- Layanan -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold tracking-widest text-slate-300 uppercase">LAYANAN</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-amber-400 transition">Pemesanan Tiket</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition">Panduan Wisata</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition">Kemitraan</a></li>
                    </ul>
                </div>

                <!-- Hubungi Kami -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold tracking-widest text-slate-300 uppercase">HUBUNGI KAMI</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>hello@cireva.id</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>+62 231 456789</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} CIREVA - Cultural events & Connections. All rights reserved.</p>
                <div class="flex items-center space-x-6">
                    <a href="#" class="hover:text-slate-400 transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-slate-400 transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-slate-400 transition">Bantuan</a>
                    <a href="#" class="hover:text-slate-400 transition">Kontak Kami</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>