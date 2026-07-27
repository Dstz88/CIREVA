<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">

            <!-- Admin Sidebar Component -->
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <main class="flex-1 space-y-6">
                <!-- Header -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Kategori event Budaya</h3>
                        <p class="text-xs text-slate-500 mt-1">Daftar kategori event yang tersedia beserta detail
                            pengajuan dari Mitra.</p>
                    </div>

                    <!-- 3 Cards Category Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                        @foreach($categories as $category)
                        <div
                            class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-slate-900">{{ $category->name }}</h4>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                        {{ $category->description ?? 'Ragam event & pagelaran seni kebudayaan khas
                                        Cirebon.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                                <span
                                    class="text-xs font-bold text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-100">
                                    {{ $category->events->count() }} Total event
                                </span>
                                <a href="{{ route('admin.categories.show', $category) }}"
                                    class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">
                                    Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </main>

        </div>
    </div>
</x-app-layout>