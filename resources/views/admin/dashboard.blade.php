<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Administrator Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Admin Sidebar Component -->
                <x-admin-sidebar />

                <!-- Main Content Area -->
                <div class="flex-1 space-y-8">
                    <!-- Welcome Header -->
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-2">
                        <h3 class="text-2xl font-black text-slate-900">Selamat Datang, Admin {{ Auth::user()->name }}!
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Panel Kendali Utama CIREVA. Pantau metrik pengguna, persetujuan organizer, verifikasi event,
                            serta laporan keuangan sistem.
                        </p>
                    </div>

                    <!-- 9 Core Metrics Grid (as per admin.md) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- 1. Total Users -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
                            <div
                                class="flex justify-between items-center text-slate-500 text-xs font-bold uppercase tracking-wider">
                                <span>Total Users (Visitor)</span>
                                <span class="p-2 rounded-xl bg-blue-50 text-blue-600">👤</span>
                            </div>
                            <div class="text-3xl font-black text-slate-900">
                                {{ number_format($metrics['total_users'] ?? 0) }}
                            </div>
                            <p class="text-[11px] text-slate-400">Pengunjung / Pembeli tiket terdaftar</p>
                        </div>

                        <!-- 2. Total Organizers -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
                            <div
                                class="flex justify-between items-center text-slate-500 text-xs font-bold uppercase tracking-wider">
                                <span>Total Organizers</span>
                                <span class="p-2 rounded-xl bg-amber-50 text-amber-600">🏛️</span>
                            </div>
                            <div class="text-3xl font-black text-slate-900">
                                {{ number_format($metrics['total_organizers'] ?? 0) }}
                            </div>
                            <p class="text-[11px] text-slate-400">Mitra penyelenggara terdaftar</p>
                        </div>

                        <!-- 3. Pending Organizer -->
                        <div
                            class="bg-white p-6 rounded-3xl shadow-sm border border-amber-200 bg-amber-50/20 space-y-2">
                            <div
                                class="flex justify-between items-center text-amber-800 text-xs font-bold uppercase tracking-wider">
                                <span>Pending Organizer</span>
                               
                            </div>
                            <div class="text-3xl font-black text-amber-700">
                                {{ number_format($metrics['pending_organizers'] ?? 0) }}
                            </div>
                            <a href="{{ route('admin.organizer-verifications.index') }}"
                                class="text-[11px] font-bold text-amber-700 hover:underline inline-block">
                                Review Verifikasi &rarr;
                            </a>
                        </div>



                        <!-- 5. Pending event -->
                        <div
                            class="bg-white p-6 rounded-3xl shadow-sm border border-amber-200 bg-amber-50/20 space-y-2">
                            <div
                                class="flex justify-between items-center text-amber-800 text-xs font-bold uppercase tracking-wider">
                                <span>Pending event</span>
                                
                            </div>
                            <div class="text-3xl font-black text-amber-700">
                                {{ number_format($metrics['pending_events'] ?? 0) }}
                            </div>
                            <a href="{{ route('admin.events.index') }}"
                                class="text-[11px] font-bold text-amber-700 hover:underline inline-block">
                                Review Pengajuan event &rarr;
                            </a>
                        </div>

                        <!-- 6. Published events -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
                            <div
                                class="flex justify-between items-center text-slate-500 text-xs font-bold uppercase tracking-wider">
                                <span>Published events</span>
                                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">🎉</span>
                            </div>
                            <div class="text-3xl font-black text-slate-900">
                                {{ number_format($metrics['published_events'] ?? 0) }}
                            </div>
                            <p class="text-[11px] text-slate-400">Event tayang di publik</p>
                        </div>

                        <!-- 7. Active Tickets -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
                            <div
                                class="flex justify-between items-center text-slate-500 text-xs font-bold uppercase tracking-wider">
                                <span>Active Tickets</span>
                                <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600">🎟️</span>
                            </div>
                            <div class="text-3xl font-black text-slate-900">
                                {{ number_format($metrics['active_tickets'] ?? 0) }}
                            </div>
                            <p class="text-[11px] text-slate-400">Varian tiket siap dibeli</p>
                        </div>

                        <!-- 8. Total Bookings -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
                            <div
                                class="flex justify-between items-center text-slate-500 text-xs font-bold uppercase tracking-wider">
                                <span>Total Bookings</span>
                                <span class="p-2 rounded-xl bg-purple-50 text-purple-600">📑</span>
                            </div>
                            <div class="text-3xl font-black text-slate-900">
                                {{ number_format($metrics['total_bookings'] ?? 0) }}
                            </div>
                            <p class="text-[11px] text-slate-400">Pemesanan masuk</p>
                        </div>

                        <!-- 9. Revenue Summary -->
                        <div
                            class="bg-slate-900 text-white p-6 rounded-3xl shadow-md border border-slate-800 space-y-2 sm:col-span-2 lg:col-span-1">
                            <div
                                class="flex justify-between items-center text-amber-400 text-xs font-bold uppercase tracking-wider">
                                <span>Revenue Summary</span>
                                <span class="p-2 rounded-xl bg-amber-400/20 text-amber-300">💰</span>
                            </div>
                            <div class="text-2xl font-black text-amber-400">
                                Rp {{ number_format($metrics['revenue_summary'] ?? 0, 0, ',', '.') }}
                            </div>
                            <p class="text-[11px] text-slate-400">Total nilai transaksi sukses</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>