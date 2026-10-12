<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        {{ $wedding->title }}
                    </h2>
                    @if ($wedding->status === 'published')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                            Published
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                            Draft
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Slug URL: <code class="text-indigo-600 font-mono font-semibold">{{ $wedding->slug }}</code> &bull; Template: {{ $wedding->template->name }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <!-- Toggle Publish Button -->
                <form method="POST" action="{{ route('weddings.publish', $wedding) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl transition {{ $wedding->status === 'published' ? 'text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100' : 'text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100' }}">
                        {{ $wedding->status === 'published' ? 'Ubah ke Draft' : 'Publish Sekarang' }}
                    </button>
                </form>

                <!-- Kelola Tamu -->
                <a href="{{ route('weddings.guests.index', $wedding) }}" class="px-3.5 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Tamu ({{ $wedding->guests->count() }})
                </a>

                <!-- Rekap RSVP -->
                <a href="{{ route('weddings.rsvps.index', $wedding) }}" class="px-3.5 py-2 text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 rounded-xl hover:bg-rose-100 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    RSVP ({{ $wedding->rsvps->count() }})
                </a>

                <!-- Preview -->
                <a href="{{ route('weddings.preview', $wedding) }}" target="_blank" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition flex items-center gap-1">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview
                </a>

                <a href="{{ route('dashboard') }}" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition">
                    &larr; Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ activeTab: 'general' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Status Alert -->
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl flex items-center justify-between shadow-sm">
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 text-sm font-semibold">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl">
                    <div class="font-bold text-rose-800 text-sm mb-1">Periksa kembali kesalahan input:</div>
                    <ul class="list-disc list-inside text-xs text-rose-700 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Management Tabs Navigation -->
            <div class="bg-white p-2 rounded-2xl border border-gray-100 shadow-sm flex flex-wrap gap-1">
                <button type="button" @click="activeTab = 'general'" class="px-4 py-2.5 rounded-xl text-xs font-semibold transition" :class="activeTab === 'general' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                    1. Info Utama & Slug
                </button>
                <button type="button" @click="activeTab = 'couple'" class="px-4 py-2.5 rounded-xl text-xs font-semibold transition" :class="activeTab === 'couple' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                    2. Data Mempelai
                </button>
                <button type="button" @click="activeTab = 'events'" class="px-4 py-2.5 rounded-xl text-xs font-semibold transition" :class="activeTab === 'events' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                    3. Sesi Acara ({{ $wedding->events->count() }})
                </button>
                <button type="button" @click="activeTab = 'galleries'" class="px-4 py-2.5 rounded-xl text-xs font-semibold transition" :class="activeTab === 'galleries' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                    4. Galeri Foto ({{ $wedding->galleries->count() }})
                </button>
                <button type="button" @click="activeTab = 'settings'" class="px-4 py-2.5 rounded-xl text-xs font-semibold transition" :class="activeTab === 'settings' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                    5. Tema & Pengaturan
                </button>
            </div>

            <!-- TAB 1: INFORMASI UTAMA & SLUG -->
            <div x-show="activeTab === 'general'" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Informasi Utama & Status Publikasi</h3>
                <p class="text-xs text-gray-500 mb-6">Atur judul undangan, slug tautan unik, status publikasi, dan template terpilih</p>

                <form method="POST" action="{{ route('weddings.update', $wedding) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title" value="Judul Undangan" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $wedding->title)" required />
                    </div>

                    <div>
                        <x-input-label for="slug" value="Slug URL Unik" />
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-gray-500 font-mono">/invitation/</span>
                            <x-text-input id="slug" name="slug" type="text" class="block w-full font-mono text-sm" :value="old('slug', $wedding->slug)" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="template_id" value="Template Desain" />
                            <select id="template_id" name="template_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                @foreach ($templates as $tmpl)
                                    <option value="{{ $tmpl->id }}" {{ $wedding->template_id == $tmpl->id ? 'selected' : '' }}>
                                        {{ $tmpl->name }} {{ $tmpl->is_premium ? '(Premium)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="status" value="Status Undangan" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="draft" {{ $wedding->status === 'draft' ? 'selected' : '' }}>Draft (Hanya terlihat oleh Anda)</option>
                                <option value="published" {{ $wedding->status === 'published' ? 'selected' : '' }}>Published (Dapat diakses publik)</option>
                                <option value="archived" {{ $wedding->status === 'archived' ? 'selected' : '' }}>Archived (Diarsipkan)</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                            Simpan Perubahan Utama
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: DATA MEMPELAI -->
            <div x-show="activeTab === 'couple'" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Data Mempelai Pria & Wanita</h3>
                <p class="text-xs text-gray-500 mb-6">Perbarui profil, nama lengkap bergelar, nama orang tua, dan foto kedua pengantin</p>

                <form method="POST" action="{{ route('weddings.couple.update', $wedding) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Mempelai Pria -->
                        <div class="p-5 rounded-2xl border border-indigo-100 bg-indigo-50/30 space-y-4">
                            <h4 class="font-bold text-sm text-indigo-900 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                Mempelai Pria
                            </h4>

                            <div>
                                <x-input-label for="groom_name" value="Nama Panggilan *" />
                                <x-text-input id="groom_name" name="groom_name" type="text" class="mt-1 block w-full bg-white" :value="old('groom_name', $wedding->couple->groom_name ?? '')" required />
                            </div>

                            <div>
                                <x-input-label for="groom_full_name" value="Nama Lengkap Bergelar *" />
                                <x-text-input id="groom_full_name" name="groom_full_name" type="text" class="mt-1 block w-full bg-white" :value="old('groom_full_name', $wedding->couple->groom_full_name ?? '')" required />
                            </div>

                            <div>
                                <x-input-label for="groom_father_name" value="Nama Ayah" />
                                <x-text-input id="groom_father_name" name="groom_father_name" type="text" class="mt-1 block w-full bg-white" :value="old('groom_father_name', $wedding->couple->groom_father_name ?? '')" />
                            </div>

                            <div>
                                <x-input-label for="groom_mother_name" value="Nama Ibu" />
                                <x-text-input id="groom_mother_name" name="groom_mother_name" type="text" class="mt-1 block w-full bg-white" :value="old('groom_mother_name', $wedding->couple->groom_mother_name ?? '')" />
                            </div>

                            <div>
                                <x-input-label for="groom_photo" value="Ganti Foto Pria" />
                                @if (!empty($wedding->couple->groom_photo))
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $wedding->couple->groom_photo) }}" alt="Groom" class="w-16 h-16 rounded-xl object-cover border" />
                                    </div>
                                @endif
                                <input type="file" id="groom_photo" name="groom_photo" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200" />
                            </div>
                        </div>

                        <!-- Mempelai Wanita -->
                        <div class="p-5 rounded-2xl border border-rose-100 bg-rose-50/30 space-y-4">
                            <h4 class="font-bold text-sm text-rose-900 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                                Mempelai Wanita
                            </h4>

                            <div>
                                <x-input-label for="bride_name" value="Nama Panggilan *" />
                                <x-text-input id="bride_name" name="bride_name" type="text" class="mt-1 block w-full bg-white" :value="old('bride_name', $wedding->couple->bride_name ?? '')" required />
                            </div>

                            <div>
                                <x-input-label for="bride_full_name" value="Nama Lengkap Bergelar *" />
                                <x-text-input id="bride_full_name" name="bride_full_name" type="text" class="mt-1 block w-full bg-white" :value="old('bride_full_name', $wedding->couple->bride_full_name ?? '')" required />
                            </div>

                            <div>
                                <x-input-label for="bride_father_name" value="Nama Ayah" />
                                <x-text-input id="bride_father_name" name="bride_father_name" type="text" class="mt-1 block w-full bg-white" :value="old('bride_father_name', $wedding->couple->bride_father_name ?? '')" />
                            </div>

                            <div>
                                <x-input-label for="bride_mother_name" value="Nama Ibu" />
                                <x-text-input id="bride_mother_name" name="bride_mother_name" type="text" class="mt-1 block w-full bg-white" :value="old('bride_mother_name', $wedding->couple->bride_mother_name ?? '')" />
                            </div>

                            <div>
                                <x-input-label for="bride_photo" value="Ganti Foto Wanita" />
                                @if (!empty($wedding->couple->bride_photo))
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $wedding->couple->bride_photo) }}" alt="Bride" class="w-16 h-16 rounded-xl object-cover border" />
                                    </div>
                                @endif
                                <input type="file" id="bride_photo" name="bride_photo" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-100 file:text-rose-700 hover:file:bg-rose-200" />
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                            Simpan Data Pengantin
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: SESI ACARA -->
            <div x-show="activeTab === 'events'" class="space-y-6">
                <!-- List Existing Events -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Daftar Sesi Acara Pernikahan</h3>
                            <p class="text-xs text-gray-500">Kelola sesi seperti Akad Nikah, Resepsi, Unduh Mantu, atau Syukuran</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse ($wedding->events as $event)
                            <div class="p-5 rounded-2xl border border-gray-200 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded uppercase bg-indigo-100 text-indigo-800">{{ $event->type }}</span>
                                        <h4 class="font-bold text-gray-900">{{ $event->title }}</h4>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        {{ $event->event_date->format('d F Y') }} &bull; Waktu: {{ substr($event->start_time, 0, 5) }} {{ $event->end_time ? '- ' . substr($event->end_time, 0, 5) : 's/d selesai' }}
                                    </div>
                                    <div class="text-xs text-gray-700">
                                        Tempat: <span class="font-semibold">{{ $event->venue }}</span> &bull; {{ $event->address }}
                                    </div>
                                    @if ($event->maps_url)
                                        <a href="{{ $event->maps_url }}" target="_blank" class="inline-block text-xs text-indigo-600 hover:underline">
                                            Buka Google Maps &rarr;
                                        </a>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('weddings.events.destroy', [$wedding, $event]) }}" onsubmit="return confirm('Hapus sesi acara ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 px-3 py-1.5 bg-rose-50 border border-rose-100 rounded-lg font-semibold transition">
                                            Hapus Sesi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 py-4 text-center">Belum ada sesi acara yang ditambahkan.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Form Add New Event -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    <h4 class="text-base font-bold text-gray-900 mb-4">+ Tambah Sesi Acara Baru</h4>
                    <form method="POST" action="{{ route('weddings.events.store', $wedding) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="new_type" value="Tipe Sesi" />
                                <select id="new_type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                    <option value="akad">Akad Nikah / Pemberkatan</option>
                                    <option value="resepsi">Resepsi Pernikahan</option>
                                    <option value="unduh_mantu">Unduh Mantu</option>
                                    <option value="lainnya">Sesi Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="new_title" value="Nama Sesi *" />
                                <x-text-input id="new_title" name="title" type="text" class="mt-1 block w-full" placeholder="Contoh: Resepsi Sesi II" required />
                            </div>
                            <div>
                                <x-input-label for="new_event_date" value="Tanggal *" />
                                <x-text-input id="new_event_date" name="event_date" type="date" class="mt-1 block w-full" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="new_start_time" value="Waktu Mulai *" />
                                <x-text-input id="new_start_time" name="start_time" type="time" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label for="new_end_time" value="Waktu Selesai" />
                                <x-text-input id="new_end_time" name="end_time" type="time" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <x-input-label for="new_venue" value="Nama Tempat *" />
                                <x-text-input id="new_venue" name="venue" type="text" class="mt-1 block w-full" placeholder="Gedung / Masjid" required />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="new_address" value="Alamat Lengkap *" />
                            <textarea id="new_address" name="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="Jl. Contoh No. 123" required></textarea>
                        </div>

                        <div>
                            <x-input-label for="new_maps_url" value="URL Google Maps" />
                            <x-text-input id="new_maps_url" name="maps_url" type="url" class="mt-1 block w-full" placeholder="https://maps.google.com/..." />
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                                Tambahkan Sesi Acara
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 4: GALERI FOTO -->
            <div x-show="activeTab === 'galleries'" class="space-y-6">
                <!-- Upload Photos -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Unggah Foto Galeri</h3>
                    <p class="text-xs text-gray-500 mb-4">Pilih satu atau beberapa foto untuk ditampilkan dalam layout galeri interaktif (Maks 2MB per foto)</p>

                    <form method="POST" action="{{ route('weddings.galleries.store', $wedding) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-end gap-4">
                        @csrf
                        <div class="flex-1 w-full">
                            <x-input-label for="gallery_images" value="Pilih File Gambar (Multiple)" />
                            <input type="file" id="gallery_images" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" required />
                        </div>
                        <div class="w-full sm:w-64">
                            <x-input-label for="gallery_caption" value="Keterangan / Caption (Opsional)" />
                            <x-text-input id="gallery_caption" name="caption" type="text" class="mt-1 block w-full" placeholder="Pre-wedding shoot" />
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                            Unggah Foto
                        </button>
                    </form>
                </div>

                <!-- Existing Gallery Grid -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    <h4 class="text-base font-bold text-gray-900 mb-4">Koleksi Foto Saat Ini ({{ $wedding->galleries->count() }})</h4>

                    @if ($wedding->galleries->isEmpty())
                        <p class="text-xs text-gray-400 py-6 text-center">Belum ada foto yang diunggah ke galeri.</p>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($wedding->galleries as $gallery)
                                <div class="group relative rounded-2xl overflow-hidden border border-gray-100 bg-gray-100 aspect-square">
                                    <img src="{{ str_starts_with($gallery->image_path, 'http') ? $gallery->image_path : asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->caption ?? 'Gallery' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                                    
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex flex-col justify-between p-3">
                                        <div class="flex justify-end">
                                            <form method="POST" action="{{ route('weddings.galleries.destroy', [$wedding, $gallery]) }}" onsubmit="return confirm('Hapus foto ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-xs shadow">
                                                    &times;
                                                </button>
                                            </form>
                                        </div>
                                        <p class="text-xs text-white truncate">{{ $gallery->caption ?? 'Foto #' . $gallery->sort_order }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- TAB 5: TEMA & PENGATURAN -->
            <div x-show="activeTab === 'settings'" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Tema Visual & Pengaturan Fitur</h3>
                <p class="text-xs text-gray-500 mb-6">Atur teks pembuka, ucapan terima kasih, palet warna, tipografi, dan audio latar belakang</p>

                <form method="POST" action="{{ route('weddings.settings.update', $wedding) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="opening_text" value="Teks Pembuka / Salam / Ayat Suci" />
                        <textarea id="opening_text" name="opening_text" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">{{ old('opening_text', $wedding->settings->opening_text ?? '') }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="closing_text" value="Teks Penutup & Ucapan Terima Kasih" />
                        <textarea id="closing_text" name="closing_text" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">{{ old('closing_text', $wedding->settings->closing_text ?? '') }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="music_url" value="URL Musik Latar (Direct MP3 URL)" />
                        <x-text-input id="music_url" name="music_url" type="url" class="mt-1 block w-full" :value="old('music_url', $wedding->settings->music_url ?? '')" placeholder="https://domain.com/musik.mp3" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
                        <div>
                            <x-input-label for="set_primary_color" value="Warna Utama (Primary Color)" />
                            <div class="flex items-center gap-2 mt-1">
                                <input type="color" id="set_primary_color" name="primary_color" value="{{ $wedding->settings->primary_color ?? '#4F46E5' }}" class="w-10 h-10 rounded-lg cursor-pointer border-0 p-0" />
                                <span class="text-xs font-mono font-semibold">{{ $wedding->settings->primary_color ?? '#4F46E5' }}</span>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="set_secondary_color" value="Warna Sekunder (Secondary Color)" />
                            <div class="flex items-center gap-2 mt-1">
                                <input type="color" id="set_secondary_color" name="secondary_color" value="{{ $wedding->settings->secondary_color ?? '#EC4899' }}" class="w-10 h-10 rounded-lg cursor-pointer border-0 p-0" />
                                <span class="text-xs font-mono font-semibold">{{ $wedding->settings->secondary_color ?? '#EC4899' }}</span>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="set_font_family" value="Font Tipografi" />
                            <select id="set_font_family" name="font_family" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="Playfair Display" {{ ($wedding->settings->font_family ?? '') === 'Playfair Display' ? 'selected' : '' }}>Playfair Display</option>
                                <option value="Cinzel" {{ ($wedding->settings->font_family ?? '') === 'Cinzel' ? 'selected' : '' }}>Cinzel</option>
                                <option value="Great Vibes" {{ ($wedding->settings->font_family ?? '') === 'Great Vibes' ? 'selected' : '' }}>Great Vibes</option>
                                <option value="Plus Jakarta Sans" {{ ($wedding->settings->font_family ?? '') === 'Plus Jakarta Sans' ? 'selected' : '' }}>Plus Jakarta Sans</option>
                            </select>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-3">
                        <div class="font-bold text-xs uppercase tracking-wider text-gray-500">Toggle Modul Undangan</div>
                        
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="show_gift" value="1" {{ ($wedding->settings->show_gift ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-xs text-gray-700 font-medium">Tampilkan Kado Pernikahan / Rekening Digital (Wedding Gift)</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="show_rsvp" value="1" {{ ($wedding->settings->show_rsvp ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-xs text-gray-700 font-medium">Aktifkan Formulir Konfirmasi Kehadiran (RSVP)</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="show_guestbook" value="1" {{ ($wedding->settings->show_guestbook ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-xs text-gray-700 font-medium">Tampilkan Dinding Ucapan Doa Tamu (Guest Book)</span>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
