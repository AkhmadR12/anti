<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Wizard Pembuatan Undangan Baru
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Lengkapi data mempelai, acara, dan desain undangan Anda dalam 7 langkah mudah.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-xl transition">
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        step: 1,
        totalSteps: 7,
        selectedTemplate: '{{ $templates->first()->id ?? 1 }}',
        title: '',
        groomName: '',
        groomFullName: '',
        brideName: '',
        brideFullName: '',
        akadDate: '',
        akadVenue: '',
        resepsiDate: '',
        resepsiVenue: '',
        primaryColor: '#4F46E5',
        secondaryColor: '#EC4899',
        fontFamily: 'Playfair Display'
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Step Progress Indicator -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600" x-text="'Langkah ' + step + ' dari ' + totalSteps"></span>
                    <span class="text-xs text-gray-400" x-text="
                        step === 1 ? 'Data Pengantin' :
                        (step === 2 ? 'Data Acara' :
                        (step === 3 ? 'Galeri Foto' :
                        (step === 4 ? 'Pengaturan & Tema' :
                        (step === 5 ? 'Pilih Template' :
                        (step === 6 ? 'Ringkasan & Preview' : 'Finalisasi & Simpan')))))
                    "></span>
                </div>
                <!-- Progress Bar -->
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300 ease-out" :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
                </div>

                <!-- Step Tabs / Pills -->
                <div class="grid grid-cols-7 gap-2 mt-4 text-center">
                    <button type="button" @click="step = 1" class="text-xs py-1.5 rounded-lg font-medium transition" :class="step === 1 ? 'bg-indigo-50 text-indigo-700 font-bold' : (step > 1 ? 'text-emerald-600' : 'text-gray-400')">
                        1. Pengantin
                    </button>
                    <button type="button" @click="step = 2" class="text-xs py-1.5 rounded-lg font-medium transition" :class="step === 2 ? 'bg-indigo-50 text-indigo-700 font-bold' : (step > 2 ? 'text-emerald-600' : 'text-gray-400')">
                        2. Acara
                    </button>
                    <button type="button" @click="step = 3" class="text-xs py-1.5 rounded-lg font-medium transition" :class="step === 3 ? 'bg-indigo-50 text-indigo-700 font-bold' : (step > 3 ? 'text-emerald-600' : 'text-gray-400')">
                        3. Galeri
                    </button>
                    <button type="button" @click="step = 4" class="text-xs py-1.5 rounded-lg font-medium transition" :class="step === 4 ? 'bg-indigo-50 text-indigo-700 font-bold' : (step > 4 ? 'text-emerald-600' : 'text-gray-400')">
                        4. Tema
                    </button>
                    <button type="button" @click="step = 5" class="text-xs py-1.5 rounded-lg font-medium transition" :class="step === 5 ? 'bg-indigo-50 text-indigo-700 font-bold' : (step > 5 ? 'text-emerald-600' : 'text-gray-400')">
                        5. Template
                    </button>
                    <button type="button" @click="step = 6" class="text-xs py-1.5 rounded-lg font-medium transition" :class="step === 6 ? 'bg-indigo-50 text-indigo-700 font-bold' : (step > 6 ? 'text-emerald-600' : 'text-gray-400')">
                        6. Preview
                    </button>
                    <button type="button" @click="step = 7" class="text-xs py-1.5 rounded-lg font-medium transition" :class="step === 7 ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-400'">
                        7. Selesai
                    </button>
                </div>
            </div>

            <!-- Validation Errors Summary -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl">
                    <div class="font-bold text-rose-800 text-sm mb-1">Terdapat kesalahan pada input form:</div>
                    <ul class="list-disc list-inside text-xs text-rose-700 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Main Wizard Form -->
            <form action="{{ route('weddings.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                @csrf

                <!-- STEP 1: DATA PENGANTIN -->
                <div x-show="step === 1" class="space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold text-gray-900">Step 1: Data Judul & Pasangan Pengantin</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Masukkan nama lengkap, nama panggilan, orang tua, dan foto mempelai</p>
                    </div>

                    <!-- Judul Undangan -->
                    <div>
                        <x-input-label for="title" value="Judul Undangan (Contoh: Pernikahan Budi & Siti)" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" x-model="title" placeholder="Pernikahan Budi & Siti" required />
                        <span class="text-xs text-gray-400 mt-1 block">Slug URL unik akan digenerate otomatis berdasarkan judul ini.</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <!-- Mempelai Pria -->
                        <div class="bg-indigo-50/50 p-5 rounded-xl border border-indigo-100 space-y-4">
                            <div class="flex items-center gap-2 text-indigo-800 font-bold text-sm">
                                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Mempelai Pria (Groom)
                            </div>

                            <div>
                                <x-input-label for="groom_name" value="Nama Panggilan Pria *" />
                                <x-text-input id="groom_name" name="groom_name" type="text" class="mt-1 block w-full bg-white" x-model="groomName" placeholder="Budi" required />
                            </div>

                            <div>
                                <x-input-label for="groom_full_name" value="Nama Lengkap Bergelar Pria *" />
                                <x-text-input id="groom_full_name" name="groom_full_name" type="text" class="mt-1 block w-full bg-white" x-model="groomFullName" placeholder="Budi Santoso, S.T." required />
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <x-input-label for="groom_father_name" value="Nama Ayah Pria" />
                                    <x-text-input id="groom_father_name" name="groom_father_name" type="text" class="mt-1 block w-full bg-white" placeholder="Bambang Santoso" />
                                </div>
                                <div>
                                    <x-input-label for="groom_mother_name" value="Nama Ibu Pria" />
                                    <x-text-input id="groom_mother_name" name="groom_mother_name" type="text" class="mt-1 block w-full bg-white" placeholder="Endang Lestari" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="groom_photo" value="Foto Mempelai Pria (Opsional)" />
                                <input type="file" id="groom_photo" name="groom_photo" accept="image/*" class="mt-1 block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200" />
                            </div>
                        </div>

                        <!-- Mempelai Wanita -->
                        <div class="bg-rose-50/50 p-5 rounded-xl border border-rose-100 space-y-4">
                            <div class="flex items-center gap-2 text-rose-800 font-bold text-sm">
                                <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Mempelai Wanita (Bride)
                            </div>

                            <div>
                                <x-input-label for="bride_name" value="Nama Panggilan Wanita *" />
                                <x-text-input id="bride_name" name="bride_name" type="text" class="mt-1 block w-full bg-white" x-model="brideName" placeholder="Siti" required />
                            </div>

                            <div>
                                <x-input-label for="bride_full_name" value="Nama Lengkap Bergelar Wanita *" />
                                <x-text-input id="bride_full_name" name="bride_full_name" type="text" class="mt-1 block w-full bg-white" x-model="brideFullName" placeholder="Siti Nurhaliza, S.E." required />
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <x-input-label for="bride_father_name" value="Nama Ayah Wanita" />
                                    <x-text-input id="bride_father_name" name="bride_father_name" type="text" class="mt-1 block w-full bg-white" placeholder="Ahmad Hidayat" />
                                </div>
                                <div>
                                    <x-input-label for="bride_mother_name" value="Nama Ibu Wanita" />
                                    <x-text-input id="bride_mother_name" name="bride_mother_name" type="text" class="mt-1 block w-full bg-white" placeholder="Rahmawati" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="bride_photo" value="Foto Mempelai Wanita (Opsional)" />
                                <input type="file" id="bride_photo" name="bride_photo" accept="image/*" class="mt-1 block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-100 file:text-rose-700 hover:file:bg-rose-200" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: DATA ACARA -->
                <div x-show="step === 2" class="space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold text-gray-900">Step 2: Data Acara Pernikahan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Atur tanggal, waktu, nama gedung/tempat, alamat, dan link Google Maps</p>
                    </div>

                    <!-- Acara Utama: Akad Nikah -->
                    <div class="p-5 bg-gray-50 rounded-xl border border-gray-200 space-y-4">
                        <div class="font-bold text-sm text-gray-800 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                            Sesi 1: Akad Nikah / Pemberkatan (Wajib)
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="akad_title" value="Judul Sesi *" />
                                <x-text-input id="akad_title" name="akad_title" type="text" class="mt-1 block w-full bg-white" value="Akad Nikah" required />
                            </div>
                            <div>
                                <x-input-label for="akad_date" value="Tanggal Acara *" />
                                <x-text-input id="akad_date" name="akad_date" type="date" class="mt-1 block w-full bg-white" x-model="akadDate" required />
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <x-input-label for="akad_start_time" value="Mulai *" />
                                    <x-text-input id="akad_start_time" name="akad_start_time" type="time" class="mt-1 block w-full bg-white" value="08:00" required />
                                </div>
                                <div>
                                    <x-input-label for="akad_end_time" value="Selesai" />
                                    <x-text-input id="akad_end_time" name="akad_end_time" type="time" class="mt-1 block w-full bg-white" value="10:00" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="akad_venue" value="Nama Tempat / Masjid / Gedung *" />
                                <x-text-input id="akad_venue" name="akad_venue" type="text" class="mt-1 block w-full bg-white" x-model="akadVenue" placeholder="Masjid Agung Al-Barkah" required />
                            </div>
                            <div>
                                <x-input-label for="akad_maps_url" value="Link Google Maps (Opsional)" />
                                <x-text-input id="akad_maps_url" name="akad_maps_url" type="url" class="mt-1 block w-full bg-white" placeholder="https://maps.google.com/?q=..." />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="akad_address" value="Alamat Lengkap *" />
                            <textarea id="akad_address" name="akad_address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white" placeholder="Jl. Veteran No. 12, Jakarta" required></textarea>
                        </div>
                    </div>

                    <!-- Acara Kedua: Resepsi Pernikahan -->
                    <div class="p-5 bg-gray-50 rounded-xl border border-gray-200 space-y-4">
                        <div class="font-bold text-sm text-gray-800 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                            Sesi 2: Resepsi Pernikahan (Opsional)
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="resepsi_title" value="Judul Sesi" />
                                <x-text-input id="resepsi_title" name="resepsi_title" type="text" class="mt-1 block w-full bg-white" value="Resepsi Pernikahan" />
                            </div>
                            <div>
                                <x-input-label for="resepsi_date" value="Tanggal Acara" />
                                <x-text-input id="resepsi_date" name="resepsi_date" type="date" class="mt-1 block w-full bg-white" x-model="resepsiDate" />
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <x-input-label for="resepsi_start_time" value="Mulai" />
                                    <x-text-input id="resepsi_start_time" name="resepsi_start_time" type="time" class="mt-1 block w-full bg-white" value="11:00" />
                                </div>
                                <div>
                                    <x-input-label for="resepsi_end_time" value="Selesai" />
                                    <x-text-input id="resepsi_end_time" name="resepsi_end_time" type="time" class="mt-1 block w-full bg-white" value="14:00" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="resepsi_venue" value="Nama Tempat / Ballroom" />
                                <x-text-input id="resepsi_venue" name="resepsi_venue" type="text" class="mt-1 block w-full bg-white" x-model="resepsiVenue" placeholder="Grand Ballroom Hotel Mulia" />
                            </div>
                            <div>
                                <x-input-label for="resepsi_maps_url" value="Link Google Maps" />
                                <x-text-input id="resepsi_maps_url" name="resepsi_maps_url" type="url" class="mt-1 block w-full bg-white" placeholder="https://maps.google.com/?q=..." />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="resepsi_address" value="Alamat Lengkap" />
                            <textarea id="resepsi_address" name="resepsi_address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white" placeholder="Jl. Asia Afrika No. 1, Senayan, Jakarta"></textarea>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: GALLERY / FOTO -->
                <div x-show="step === 3" class="space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold text-gray-900">Step 3: Galeri Foto Pasangan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Unggah beberapa foto kenangan atau pre-wedding (Maksimal 10 foto, format JPG/PNG/WEBP, maks 2MB per file)</p>
                    </div>

                    <div class="p-8 border-2 border-dashed border-gray-300 rounded-2xl text-center bg-gray-50/50 hover:bg-gray-50 transition">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm font-semibold text-gray-700 mb-1">Pilih Beberapa Foto Sekaligus</p>
                        <p class="text-xs text-gray-400 mb-4">Anda juga dapat mengelola, menambah, atau menghapus foto ini kapan saja setelah undangan dibuat.</p>
                        <input type="file" name="gallery_images[]" multiple accept="image/jpeg,image/png,image/webp" class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" />
                    </div>
                </div>

                <!-- STEP 4: PENGATURAN & TEMA -->
                <div x-show="step === 4" class="space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold text-gray-900">Step 4: Pengaturan Teks, Musik & Tema Visual</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Personalisasi ucapan pembuka, audio latar belakang, serta warna utama undangan</p>
                    </div>

                    <div>
                        <x-input-label for="opening_text" value="Teks / Salam Pembuka / Ayat Suci" />
                        <textarea id="opening_text" name="opening_text" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang. (QS. Ar-Rum: 21)</textarea>
                    </div>

                    <div>
                        <x-input-label for="closing_text" value="Teks Penutup & Ucapan Terima Kasih" />
                        <textarea id="closing_text" name="closing_text" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kedua mempelai.</textarea>
                    </div>

                    <div>
                        <x-input-label for="music_url" value="URL Musik Latar (File MP3 Langsung)" />
                        <x-text-input id="music_url" name="music_url" type="url" class="mt-1 block w-full" value="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" placeholder="https://domain.com/lagu-romantis.mp3" />
                        <span class="text-xs text-gray-400 mt-1 block">Tamu dapat memutar atau mematikan musik saat membuka undangan.</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div>
                            <x-input-label for="primary_color" value="Warna Utama (Aksen Tombol & Judul)" />
                            <div class="flex items-center gap-2 mt-1">
                                <input type="color" id="primary_color" name="primary_color" x-model="primaryColor" class="w-10 h-10 rounded-lg cursor-pointer border-0 p-0" />
                                <input type="text" x-model="primaryColor" class="text-xs uppercase font-mono rounded-lg border-gray-300 w-24" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="secondary_color" value="Warna Sekunder (Aksen Subtitle)" />
                            <div class="flex items-center gap-2 mt-1">
                                <input type="color" id="secondary_color" name="secondary_color" x-model="secondaryColor" class="w-10 h-10 rounded-lg cursor-pointer border-0 p-0" />
                                <input type="text" x-model="secondaryColor" class="text-xs uppercase font-mono rounded-lg border-gray-300 w-24" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="font_family" value="Jenis Font Tipografi" />
                            <select id="font_family" name="font_family" x-model="fontFamily" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm">
                                <option value="Playfair Display">Playfair Display (Klasik & Romantis)</option>
                                <option value="Cinzel">Cinzel (Luxury & Royal)</option>
                                <option value="Great Vibes">Great Vibes (Kaligrafi Anggun)</option>
                                <option value="Plus Jakarta Sans">Plus Jakarta Sans (Modern & Bersih)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- STEP 5: PILIH TEMPLATE -->
                <div x-show="step === 5" class="space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold text-gray-900">Step 5: Pilih Desain Template Undangan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Pilih gaya visual template favorit Anda (Anda selalu dapat mengubahnya nanti)</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($templates as $tmpl)
                            <label class="relative block rounded-2xl border-2 p-4 cursor-pointer transition hover:shadow-md" :class="selectedTemplate == '{{ $tmpl->id }}' ? 'border-indigo-600 bg-indigo-50/30' : 'border-gray-200 bg-white'">
                                <input type="radio" name="template_id" value="{{ $tmpl->id }}" x-model="selectedTemplate" class="sr-only" />
                                
                                <div class="w-full h-32 bg-gradient-to-br from-indigo-100 to-rose-100 rounded-xl mb-3 flex items-center justify-center text-gray-600 font-bold text-sm">
                                    {{ $tmpl->name }}
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="font-bold text-sm text-gray-900">{{ $tmpl->name }}</div>
                                    <span x-show="selectedTemplate == '{{ $tmpl->id }}'" class="w-5 h-5 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs">✓</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $tmpl->description }}</p>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- STEP 6: PREVIEW & RINGKASAN -->
                <div x-show="step === 6" class="space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold text-gray-900">Step 6: Ringkasan & Konfirmasi Data</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Periksa kembali data yang telah Anda masukkan sebelum finalisasi</p>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 space-y-4 text-sm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-400 block">Judul Undangan:</span>
                                <span class="font-bold text-gray-900" x-text="title || 'Belum diisi'"></span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Pasangan Mempelai:</span>
                                <span class="font-bold text-gray-900" x-text="(groomName || 'Pria') + ' & ' + (brideName || 'Wanita')"></span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Nama Lengkap Mempelai Pria:</span>
                                <span class="font-medium text-gray-700" x-text="groomFullName || '-'"></span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Nama Lengkap Mempelai Wanita:</span>
                                <span class="font-medium text-gray-700" x-text="brideFullName || '-'"></span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Tanggal Akad:</span>
                                <span class="font-medium text-gray-700" x-text="akadDate || '-'"></span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Tempat Akad:</span>
                                <span class="font-medium text-gray-700" x-text="akadVenue || '-'"></span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200 flex items-center gap-4">
                            <span class="text-xs text-gray-500">Warna Aksen:</span>
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full inline-block border" :style="'background-color: ' + primaryColor"></span>
                                <span class="w-5 h-5 rounded-full inline-block border" :style="'background-color: ' + secondaryColor"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 7: FINALISASI & PUBLISH -->
                <div x-show="step === 7" class="space-y-6 text-center py-4">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900">Semua Data Siap Disimpan!</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto">
                        Setelah disimpan, undangan Anda akan berada dalam mode <strong>Draft</strong>. Anda dapat mengedit, menambah daftar tamu, atau langsung mempublikasikannya.
                    </p>

                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-base rounded-xl shadow-md hover:shadow-lg transition">
                            Simpan & Buat Undangan
                        </button>
                    </div>
                </div>

                <!-- Wizard Navigation Buttons (Prev / Next) -->
                <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-between">
                    <button type="button" @click="step--" x-show="step > 1" class="px-5 py-2.5 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        &larr; Kembali
                    </button>
                    <div x-show="step === 1"></div>

                    <button type="button" @click="step++" x-show="step < 7" class="px-6 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                        Lanjut ke Step Berikutnya &rarr;
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
