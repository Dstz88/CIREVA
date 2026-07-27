<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Edit event Budaya') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Component -->
                <x-organizer-sidebar />

                <!-- Main Form Area -->
                <div class="flex-1 space-y-6" x-data="{ showLocationModal: false }">
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-6">

                        <div class="border-b border-gray-100 pb-4">
                            <h3 class="text-xl font-bold text-slate-900">Perbarui Data event: {{ $event->title }}</h3>
                            <p class="text-xs text-slate-500 mt-1">Ubah rincian event, jadwal, atau lokasi event.</p>
                        </div>

                        @php
                        $categories = \App\Models\eventCategory::all();
                        $locations = \App\Models\eventLocation::all();
                        @endphp

                        <form action="{{ route('organizer.events.update', $event) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Judul event -->
                                <div class="md:col-span-2">
                                    <x-input-label for="title" value="Judul event / Pertunjukan"
                                        class="font-bold text-xs text-slate-700" />
                                    <x-text-input id="title" name="title" type="text"
                                        class="mt-1 block w-full text-xs rounded-xl"
                                        :value="old('title', $event->title)" required />
                                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                                </div>

                                <!-- Kategori event -->
                                <div>
                                    <x-input-label for="event_category_id" value="Kategori Budaya"
                                        class="font-bold text-xs text-slate-700" />
                                    <select id="event_category_id" name="event_category_id" required
                                        class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs">
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('event_category_id', $event->
                                            event_category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Lokasi event -->
                                <div>
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
                                        @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ old('event_location_id', $event->
                                            event_location_id) == $loc->id ? 'selected' : '' }}>
                                            {{ $loc->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Deskripsi event -->
                                <div class="md:col-span-2">
                                    <x-input-label for="description" value="Deskripsi & Rundown event"
                                        class="font-bold text-xs text-slate-700" />
                                    <textarea id="description" name="description" rows="5" required
                                        class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs">{{ old('description', $event->description) }}</textarea>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <a href="{{ route('organizer.dashboard') }}"
                                    class="text-xs text-slate-500 font-bold hover:underline">
                                    &larr; Batal & Kembali
                                </a>
                                <button type="submit"
                                    class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition shadow-sm">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>

                    </div>

                    <!-- Modal Tambah Lokasi -->
                    <div x-show="showLocationModal"
                        class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4" x-cloak>
                        <div class="bg-white rounded-3xl p-6 max-w-md w-full space-y-4 shadow-xl">
                            <h4 class="font-bold text-slate-900 text-base">Tambah Lokasi event Baru</h4>
                            <form action="{{ route('organizer.locations.store') }}" method="POST" class="space-y-4">
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
                </div>

            </div>
        </div>
    </div>
</x-app-layout>