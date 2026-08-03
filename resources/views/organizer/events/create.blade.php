<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Buat event Budaya Baru') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Component -->
                <x-organizer-sidebar />

                <!-- Main Form Area -->
                <div class="flex-1 space-y-6">
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-6">

                        <div class="border-b border-gray-100 pb-4 flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Formulir Pengajuan event</h3>
                                <p class="text-xs text-slate-500 mt-1">Lengkapi rincian agenda, lokasi, dan kategori
                                    event untuk diajukan ke Admin.</p>
                            </div>
                        </div>

                        <!-- Flash Messages -->
                        @if(session('success'))
                        <div
                            class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-xs font-bold border border-emerald-200">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if($errors->any())
                        <div
                            class="bg-rose-50 text-rose-800 p-4 rounded-2xl text-xs font-bold border border-rose-200 space-y-1">
                            <div class="font-extrabold text-rose-900">Terjadi Kesalahan Validasi:</div>
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @php
                        $categories = \App\Models\eventCategory::all();
                        $locations = \App\Models\eventLocation::all();
                        @endphp

                        <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-6" x-data="{ isPaid: '{{ old('is_paid', '1') }}' }">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Judul event -->
                                <div class="md:col-span-2">
                                    <x-input-label for="title" value="Judul Event / Pertunjukan"
                                        class="font-bold text-xs text-slate-700" />
                                    <x-text-input id="title" name="title" type="text"
                                        class="mt-1 block w-full text-xs rounded-xl" :value="old('title')" required
                                        placeholder="Contoh: Festival Tari Topeng Cirebon 2026" />
                                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                                </div>

                                <!-- Kategori event -->
                                <div>
                                    <x-input-label for="event_category_id" value="Kategori Budaya"
                                        class="font-bold text-xs text-slate-700 mb-1" />
                                    <select id="event_category_id" name="event_category_id" required
                                        class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('event_category_id')==$cat->id ?
                                            'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('event_category_id')" class="mt-1" />
                                </div>

                                <!-- Lokasi event -->
                                <div x-data="{ showLocationModal: false }">
                                    <div class="flex justify-between items-center mb-1">
                                        <x-input-label for="event_location_id" value="Lokasi Pelaksanaan"
                                            class="font-bold text-xs text-slate-700" />
                                        <button type="button" @click="showLocationModal = true"
                                            class="text-[11px] font-bold text-amber-600 hover:text-amber-700 underline">
                                            + Tambah Lokasi Baru
                                        </button>
                                    </div>
                                    <select id="event_location_id" name="event_location_id" required
                                        class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs">
                                        <option value="">-- Pilih Lokasi --</option>
                                        @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ old('event_location_id')==$loc->id ?
                                            'selected' : '' }}>
                                            {{ $loc->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('event_location_id')" class="mt-1" />

                                    <!-- Modal Tambah Lokasi -->
                                    <template x-teleport="body">
                                        <div x-show="showLocationModal"
                                            class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4"
                                            x-cloak>
                                            <div class="bg-white rounded-3xl p-6 max-w-md w-full space-y-4 shadow-xl">
                                                <h4 class="font-bold text-slate-900 text-base">Tambah Lokasi Event Baru
                                                </h4>
                                                <form action="{{ route('organizer.locations.store') }}" method="POST"
                                                    class="space-y-4">
                                                    @csrf
                                                    <div>
                                                        <x-input-label for="loc_name" value="Nama Tempat / Gedung"
                                                            class="font-bold text-xs text-slate-700" />
                                                        <x-text-input id="loc_name" name="name" type="text" required
                                                            class="mt-1 block w-full text-xs rounded-xl"
                                                            placeholder="Contoh: Gedung Nyi Mas Gandasari" />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="loc_address" value="Alamat Lengkap"
                                                            class="font-bold text-xs text-slate-700" />
                                                        <textarea id="loc_address" name="address" rows="3" required
                                                            class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs"
                                                            placeholder="Jl. Siliwangi No. 123, Cirebon"></textarea>
                                                    </div>
                                                    <div class="flex justify-end gap-2 pt-2">
                                                        <button type="button" @click="showLocationModal = false"
                                                            class="bg-gray-100 hover:bg-gray-200 text-slate-700 font-bold px-4 py-2 rounded-xl text-xs">
                                                            Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-2 rounded-xl text-xs">
                                                            Simpan Lokasi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Jenis event (Berbayar / Gratis) -->
                                <div>
                                    <x-input-label value="Jenis Tiket Masuk Event"
                                        class="font-bold text-xs text-slate-700 mb-1" />
                                    <div
                                        class="flex items-center gap-6 mt-2 p-3 bg-slate-50 rounded-xl border border-gray-200">
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="is_paid" value="1" x-model="isPaid"
                                                class="text-amber-600 focus:ring-amber-500">
                                            <span class="text-xs font-bold text-slate-800">Berbayar (Paid)</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="is_paid" value="0" x-model="isPaid"
                                                class="text-amber-600 focus:ring-amber-500">
                                            <span class="text-xs font-bold text-slate-800">Gratis (Free Entry)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Harga Tiket (Ditampilkan Jika Berbayar) -->
                                <div x-show="isPaid == '1'" x-cloak>
                                    <x-input-label for="price" value="Harga Tiket Masuk (Rp)"
                                        class="font-bold text-xs text-slate-700 mb-1" />
                                    <div class="relative mt-1">
                                        <span
                                            class="absolute left-3.5 top-2.5 text-xs font-extrabold text-slate-500">Rp</span>
                                        <x-text-input id="price" name="price" type="number" min="0" step="1000"
                                            class="pl-10 block w-full text-xs rounded-xl" :value="old('price', 50000)"
                                            placeholder="Contoh: 50000" />
                                    </div>
                                    <x-input-error :messages="$errors->get('price')" class="mt-1" />
                                </div>

                                <!-- Kapasitas Penonton / Kuota -->
                                <div class="md:col-span-2">
                                    <x-input-label for="capacity" value="Kapasitas Kuota Penonton / Pengunjung (Orang)"
                                        class="font-bold text-xs text-slate-700" />
                                    <x-text-input id="capacity" name="capacity" type="number" min="1"
                                        class="mt-1 block w-full text-xs rounded-xl" :value="old('capacity', 500)"
                                        required placeholder="Contoh: 500" />
                                    <x-input-error :messages="$errors->get('capacity')" class="mt-1" />
                                </div>

                                <!-- Tanggal Mulai -->
                                <div>
                                    <x-input-label for="start_date" value="Tanggal & Waktu Mulai"
                                        class="font-bold text-xs text-slate-700" />
                                    <input type="datetime-local" id="start_date" name="start_date" required
                                        class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs"
                                        value="{{ old('start_date') }}">
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-1" />
                                </div>

                                <!-- Tanggal Selesai -->
                                <div>
                                    <x-input-label for="end_date" value="Tanggal & Waktu Selesai"
                                        class="font-bold text-xs text-slate-700" />
                                    <input type="datetime-local" id="end_date" name="end_date" required
                                        class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs"
                                        value="{{ old('end_date') }}">
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-1" />
                                </div>

                                <!-- Banner / Poster Gambar -->
                                <div class="md:col-span-2">
                                    <x-input-label for="image" value="Banner / Poster event (JPG, PNG max 5MB)"
                                        class="font-bold text-xs text-slate-700" />
                                    <input type="file" id="image" name="image"
                                        class="mt-1 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                </div>

                                <!-- Deskripsi event -->
                                <div class="md:col-span-2">
                                    <x-input-label for="description" value="Deskripsi Event"
                                        class="font-bold text-xs text-slate-700" />
                                    <textarea id="description" name="description" rows="5" required
                                        class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs"
                                        placeholder="Jelaskan daya tarik event, seniman pengisi event, dan rincian fasilitas...">{{ old('description') }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <a href="{{ route('organizer.dashboard') }}"
                                    class="text-xs text-slate-500 font-bold hover:underline">
                                    &larr; Batal & Kembali
                                </a>
                                <button type="submit"
                                    class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition shadow-sm">
                                    Kirim Pengajuan event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>