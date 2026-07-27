<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Kelola Profil Organizer (Mitra)') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Component -->
                <x-organizer-sidebar />

                <!-- Main Content -->
                <div class="flex-1 space-y-8" x-data="{ previewUrl: null, previewTitle: '' }">

                    <!-- Flash Messages -->
                    @if(session('success'))
                    <div
                        class="bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-xs font-bold border border-emerald-200">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Status Banner -->
                    <div
                        class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Status Verifikasi Mitra</h3>
                            <p class="text-xs text-slate-500 mt-1">Status verifikasi dokumen & profil oleh Administrator
                                CIREVA.</p>
                        </div>
                        <span class="px-4 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full 
                            @if($profile->status->value === 'approved' || $profile->status->value === 'verified') bg-emerald-100 text-emerald-800 border border-emerald-200
                            @elseif($profile->status->value === 'pending') bg-amber-100 text-amber-800 border border-amber-200
                            @elseif($profile->status->value === 'rejected') bg-rose-100 text-rose-800 border border-rose-200
                            @else bg-gray-100 text-gray-800 @endif">
                            STATUS: {{ strtoupper($profile->status->value) }}
                        </span>
                    </div>

                    <!-- Form Data Diri Organisasi (Ubah Profile) -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                        <div class="p-6 md:p-8 space-y-6">
                            <div class="border-b border-gray-100 pb-4">
                                <h3 class="text-lg font-extrabold text-slate-900">Data Diri Organisasi & Penanggung
                                    Jawab</h3>
                                <p class="text-xs text-slate-500">Lengkapi data organisasi untuk keperluan verifikasi
                                    dan pembuatan surat kerjasama (SPK).</p>
                            </div>

                            <form action="{{ route('organizer.profile.update') }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nama Organisasi -->
                                    <div>
                                        <x-input-label for="organization_name" value="Nama Organisasi / Komunitas"
                                            class="font-bold text-xs text-slate-700" />
                                        <x-text-input id="organization_name" name="organization_name" type="text"
                                            class="mt-1 block w-full text-xs rounded-xl"
                                            :value="old('organization_name', $profile->organization_name)" required
                                            placeholder="Contoh: Sanggar Seni Topeng Cirebon" />
                                        <x-input-error :messages="$errors->get('organization_name')" class="mt-1" />
                                    </div>

                                    <!-- Nama Penanggung Jawab -->
                                    <div>
                                        <x-input-label for="owner_name" value="Nama Penanggung Jawab"
                                            class="font-bold text-xs text-slate-700" />
                                        <x-text-input id="owner_name" name="owner_name" type="text"
                                            class="mt-1 block w-full text-xs rounded-xl"
                                            :value="old('owner_name', $profile->owner_name ?? Auth::user()->name)"
                                            required placeholder="Nama lengkap sesuai KTP" />
                                        <x-input-error :messages="$errors->get('owner_name')" class="mt-1" />
                                    </div>

                                    <!-- Nomor Telepon -->
                                    <div>
                                        <x-input-label for="phone" value="Nomor Telepon / WhatsApp"
                                            class="font-bold text-xs text-slate-700" />
                                        <x-text-input id="phone" name="phone" type="text"
                                            class="mt-1 block w-full text-xs rounded-xl"
                                            :value="old('phone', $profile->phone)" required placeholder="08123456789" />
                                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                                    </div>

                                    <!-- Kategori event Budaya Utama -->
                                    <div>
                                        <x-input-label value="Kategori event Budaya Utama"
                                            class="font-bold text-xs text-slate-700" />
                                        <select name="culture_category"
                                            class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs">
                                            <option value="Kesenian Budaya">Kesenian Budaya (Tari, Musik Gamelan,
                                                Wayang)</option>
                                            <option value="Festival Budaya">Festival Budaya (Pameran, Kuliner, Pawai)
                                            </option>
                                            <option value="Ritual Adat">Ritual Adat (Sedekah Bumi, Panjang Jimat)
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Alamat Organisasi -->
                                    <div class="md:col-span-2">
                                        <x-input-label for="address" value="Alamat Lengkap Organisasi"
                                            class="font-bold text-xs text-slate-700" />
                                        <textarea id="address" name="address"
                                            class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs"
                                            rows="3" required
                                            placeholder="Alamat sekretariat / kantor organisasi">{{ old('address', $profile->address) }}</textarea>
                                        <x-input-error :messages="$errors->get('address')" class="mt-1" />
                                    </div>

                                    <!-- Deskripsi Organisasi -->
                                    <div class="md:col-span-2">
                                        <x-input-label for="description" value="Deskripsi / Profil Singkat Organisasi"
                                            class="font-bold text-xs text-slate-700" />
                                        <textarea id="description" name="description"
                                            class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs"
                                            rows="3"
                                            placeholder="Ceritakan latar belakang dan kegiatan seni/budaya yang dikelola">{{ old('description', $profile->description) }}</textarea>
                                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                                    </div>
                                </div>

                                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                                    <button type="submit"
                                        class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition shadow-sm">
                                        Simpan Perubahan Profil
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Upload Dokumen Pendukung Section -->
                    <div
                        class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-6 md:p-8 space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h3 class="text-lg font-extrabold text-slate-900">Upload Dokumen Pendukung Verifikasi</h3>
                            <p class="text-xs text-slate-500">Unggah KTP Penanggung Jawab, Surat Izin Organisasi, atau
                                Akta Pendirian Sanggar/Komunitas.</p>
                        </div>

                        <form action="{{ route('organizer.documents.store') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="document_type" value="Jenis Dokumen"
                                        class="font-bold text-xs text-slate-700" />
                                    <select id="document_type" name="document_type"
                                        class="mt-1 block w-full border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-xs">
                                        <option value="KTP Penanggung Jawab">KTP Penanggung Jawab</option>
                                        <option value="Surat Izin Organisasi">Surat Izin Organisasi / Komunitas</option>
                                        <option value="Dokumen Legalitas">Dokumen Legalitas / Akta Sanggar</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="file" value="File Dokumen (PDF, JPG, PNG max 5MB)"
                                        class="font-bold text-xs text-slate-700" />
                                    <input type="file" id="file" name="file" required
                                        class="mt-1 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition shadow-sm">
                                    Unggah Dokumen
                                </button>
                            </div>
                        </form>

                        <!-- Tabel Dokumen Terunggah -->
                        <div class="pt-6 border-t border-gray-100 space-y-3">
                            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Daftar Dokumen Yang
                                Sudah Diunggah:</h4>
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-2xl overflow-hidden text-xs">
                                    <thead class="bg-slate-900 text-white">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-bold uppercase">Jenis Dokumen</th>
                                            <th class="px-4 py-3 text-left font-bold uppercase">Tanggal Upload</th>
                                            <th class="px-4 py-3 text-left font-bold uppercase">Status</th>
                                            <th class="px-4 py-3 text-right font-bold uppercase">Aksi Pratinjau</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100 text-slate-700">
                                        @forelse($profile->documents as $doc)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-4 py-3 font-bold text-slate-900">
                                                {{ $doc->document_type }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-bold text-slate-900">{{ $doc->created_at->format('d M Y
                                                    - H:i:s') }} WIB</div>
                                                <div class="text-[10px] text-amber-700 font-extrabold mt-0.5">⏱ {{
                                                    $doc->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                $docStatus = $doc->verification_status->value ?? (string)
                                                $doc->verification_status;
                                                @endphp
                                                <span class="px-2.5 py-0.5 inline-flex text-[10px] font-bold rounded-full 
                                                        @if($docStatus === 'approved') bg-emerald-100 text-emerald-800
                                                        @elseif($docStatus === 'pending') bg-amber-100 text-amber-800
                                                        @else bg-rose-100 text-rose-800 @endif">
                                                    {{ strtoupper($docStatus) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <button type="button"
                                                    @click="previewUrl = '{{ asset('storage/' . $doc->file_path) }}'; previewTitle = '{{ $doc->document_type }} (Waktu Upload: {{ $doc->created_at->format('d M Y - H:i:s') }} WIB)'"
                                                    class="bg-slate-900 hover:bg-slate-800 text-white text-[11px] font-bold px-3 py-1.5 rounded-xl transition inline-flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <span>Preview Dokumen</span>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">
                                                Belum ada dokumen yang diunggah. Silakan unggah dokumen KTP atau Surat
                                                Izin.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- Modal Preview Dokumen -->
                    <div x-show="previewUrl"
                        class="fixed inset-0 z-50 bg-slate-900/80 flex items-center justify-center p-4" x-cloak>
                        <div
                            class="bg-white rounded-3xl p-6 max-w-4xl w-full space-y-4 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                            <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                <h4 class="font-bold text-slate-900 text-sm"
                                    x-text="'Pratinjau Dokumen: ' + previewTitle"></h4>
                                <button type="button" @click="previewUrl = null"
                                    class="text-slate-400 hover:text-slate-600 font-bold text-lg px-2">✕</button>
                            </div>
                            <div
                                class="flex-1 overflow-auto bg-slate-100 rounded-2xl p-2 flex items-center justify-center min-h-[400px]">
                                <template x-if="previewUrl && previewUrl.endsWith('.pdf')">
                                    <iframe :src="previewUrl" class="w-full h-[500px] rounded-xl"></iframe>
                                </template>
                                <template x-if="previewUrl && !previewUrl.endsWith('.pdf')">
                                    <img :src="previewUrl"
                                        class="max-w-full max-h-[500px] object-contain rounded-xl shadow-md">
                                </template>
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <a :href="previewUrl" target="_blank"
                                    class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
                                    Buka di Tab Baru ↗
                                </a>
                                <button type="button" @click="previewUrl = null"
                                    class="bg-slate-900 text-white font-bold px-4 py-2 rounded-xl text-xs">
                                    Tutup Pratinjau
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>