<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        Manajemen Tamu Undangan
                    </h2>
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold bg-indigo-50 text-indigo-700">
                        {{ $wedding->title }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Kelola daftar tamu, buat tautan undangan personal, dan bagikan undangan langsung via WhatsApp.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('weddings.edit', $wedding) }}" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    &larr; Editor Undangan
                </a>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        showAddModal: false,
        showImportModal: false,
        editGuest: null,
        copiedGuestId: null,
        copyToClipboard(url, guestId) {
            navigator.clipboard.writeText(url);
            this.copiedGuestId = guestId;
            setTimeout(() => { this.copiedGuestId = null; }, 2000);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl flex items-center justify-between shadow-sm">
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl">
                    <div class="font-bold text-rose-800 text-sm mb-1">Periksa kembali kesalahan:</div>
                    <ul class="list-disc list-inside text-xs text-rose-700 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- KPI Status Metrics -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                <a href="{{ route('weddings.guests.index', $wedding) }}" class="bg-white p-4 rounded-2xl border shadow-sm transition hover:shadow {{ empty($statusFilter) ? 'border-indigo-500 ring-2 ring-indigo-100' : 'border-gray-100' }}">
                    <div class="text-xl font-bold text-gray-900">{{ $counts['total'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Semua Tamu</div>
                </a>

                <a href="{{ route('weddings.guests.index', [$wedding, 'status' => 'pending']) }}" class="bg-white p-4 rounded-2xl border shadow-sm transition hover:shadow {{ $statusFilter === 'pending' ? 'border-gray-500 ring-2 ring-gray-100' : 'border-gray-100' }}">
                    <div class="text-xl font-bold text-gray-600">{{ $counts['pending'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Belum Dikirim</div>
                </a>

                <a href="{{ route('weddings.guests.index', [$wedding, 'status' => 'sent']) }}" class="bg-white p-4 rounded-2xl border shadow-sm transition hover:shadow {{ $statusFilter === 'sent' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-gray-100' }}">
                    <div class="text-xl font-bold text-blue-600">{{ $counts['sent'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Terkirim</div>
                </a>

                <a href="{{ route('weddings.guests.index', [$wedding, 'status' => 'opened']) }}" class="bg-white p-4 rounded-2xl border shadow-sm transition hover:shadow {{ $statusFilter === 'opened' ? 'border-amber-500 ring-2 ring-amber-100' : 'border-gray-100' }}">
                    <div class="text-xl font-bold text-amber-600">{{ $counts['opened'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Sudah Dibuka</div>
                </a>

                <a href="{{ route('weddings.guests.index', [$wedding, 'status' => 'confirmed']) }}" class="bg-white p-4 rounded-2xl border shadow-sm transition hover:shadow {{ $statusFilter === 'confirmed' ? 'border-emerald-500 ring-2 ring-emerald-100' : 'border-gray-100' }}">
                    <div class="text-xl font-bold text-emerald-600">{{ $counts['confirmed'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Dikonfirmasi (RSVP)</div>
                </a>
            </div>

            <!-- Toolbar & Actions -->
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search Form -->
                <form method="GET" action="{{ route('weddings.guests.index', $wedding) }}" class="w-full md:w-80 flex items-center gap-2">
                    @if ($statusFilter)
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif
                    <x-text-input name="q" type="text" value="{{ $search ?? '' }}" placeholder="Cari nama tamu..." class="text-xs w-full py-2" />
                    <button type="submit" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                        Cari
                    </button>
                    @if ($search)
                        <a href="{{ route('weddings.guests.index', $wedding) }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                    @endif
                </form>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                    <button @click="showImportModal = true" class="px-4 py-2.5 text-xs font-semibold text-gray-700 bg-gray-50 border border-gray-200 hover:bg-gray-100 rounded-xl transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Import CSV
                    </button>

                    <button @click="showAddModal = true" class="px-4 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Tamu
                    </button>
                </div>
            </div>

            <!-- Guest List Table -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                @if ($guests->isEmpty())
                    <div class="text-center py-16 px-4">
                        <p class="text-sm font-semibold text-gray-700 mb-1">Belum ada data tamu</p>
                        <p class="text-xs text-gray-400 mb-4">Tambahkan tamu satu per satu atau import sekaligus melalui berkas CSV.</p>
                        <button @click="showAddModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl">
                            + Tambah Tamu Pertama
                        </button>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500 font-semibold uppercase tracking-wider text-[10px]">
                                    <th class="p-4">Nama Tamu</th>
                                    <th class="p-4">WhatsApp</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Tautan Personal Undangan</th>
                                    <th class="p-4 text-center">Bagikan</th>
                                    <th class="p-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($guests as $g)
                                    <tr class="hover:bg-gray-50/60 transition">
                                        <!-- Nama -->
                                        <td class="p-4 font-bold text-gray-900">
                                            {{ $g->name }}
                                        </td>

                                        <!-- WhatsApp -->
                                        <td class="p-4 text-gray-600 font-mono">
                                            {{ $g->whatsapp_number ? '+' . $g->whatsapp_number : '-' }}
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="p-4">
                                            @if ($g->status === 'confirmed')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                    Dikonfirmasi
                                                </span>
                                            @elseif ($g->status === 'opened')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                                    Dibuka
                                                </span>
                                            @elseif ($g->status === 'sent')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                                    Terkirim
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Personal URL -->
                                        <td class="p-4">
                                            <div class="flex items-center gap-1.5 max-w-xs">
                                                <span class="truncate font-mono text-indigo-600 text-[11px] bg-indigo-50/70 px-2 py-1 rounded-lg">
                                                    /{{ $g->slug }}
                                                </span>
                                                <button @click="copyToClipboard('{{ $g->invitation_url }}', {{ $g->id }})" title="Salin Tautan Lengkap" class="p-1 rounded-lg hover:bg-gray-200 text-gray-500 transition">
                                                    <template x-if="copiedGuestId === {{ $g->id }}">
                                                        <span class="text-[10px] text-emerald-600 font-bold">Tersalin!</span>
                                                    </template>
                                                    <template x-if="copiedGuestId !== {{ $g->id }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </template>
                                                </button>
                                            </div>
                                        </td>

                                        <!-- WhatsApp Share Button -->
                                        <td class="p-4 text-center">
                                            <div class="inline-flex items-center gap-1">
                                                <a href="{{ $g->share_url }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[11px] rounded-xl shadow-sm transition">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.203c.043.072.043.419-.101.824z"/>
                                                    </svg>
                                                    Kirim WA
                                                </a>

                                                @if ($g->status === 'pending')
                                                    <form method="POST" action="{{ route('weddings.guests.mark-sent', [$wedding, $g]) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" title="Tandai Sudah Terkirim" class="p-1.5 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-gray-100 transition">
                                                            ✓
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="p-4 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <!-- Edit Trigger -->
                                                <button @click="editGuest = {{ json_encode($g) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-[11px]">
                                                    Edit
                                                </button>

                                                <!-- Delete -->
                                                <form method="POST" action="{{ route('weddings.guests.destroy', [$wedding, $g]) }}" onsubmit="return confirm('Hapus data tamu ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-[11px]">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-t border-gray-100">
                        {{ $guests->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- MODAL TAMBAH TAMU -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4" x-cloak>
            <div @click.away="showAddModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-sm">Tambah Tamu Undangan Baru</h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('weddings.guests.store', $wedding) }}" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="add_name" value="Nama Lengkap Tamu *" />
                        <x-text-input id="add_name" name="name" type="text" class="mt-1 block w-full text-xs" placeholder="Contoh: Bapak Ir. Bambang & Keluarga" required />
                    </div>

                    <div>
                        <x-input-label for="add_whatsapp" value="Nomor WhatsApp (Opsional)" />
                        <x-text-input id="add_whatsapp" name="whatsapp_number" type="text" class="mt-1 block w-full text-xs" placeholder="081234567890" />
                        <span class="text-[11px] text-gray-400 block mt-1">Otomatis dinormalisasi ke format internasional (628...).</span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">
                            Simpan Tamu
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT TAMU -->
        <div x-show="editGuest" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4" x-cloak>
            <div @click.away="editGuest = null" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 space-y-4" x-show="editGuest">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-sm">Edit Data Tamu</h3>
                    <button @click="editGuest = null" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                </div>

                <form method="POST" :action="'{{ url('/weddings/' . $wedding->id . '/guests') }}/' + (editGuest ? editGuest.id : '')" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="edit_name" value="Nama Lengkap Tamu *" />
                        <x-text-input id="edit_name" name="name" type="text" class="mt-1 block w-full text-xs" x-model="editGuest.name" required />
                    </div>

                    <div>
                        <x-input-label for="edit_whatsapp" value="Nomor WhatsApp" />
                        <x-text-input id="edit_whatsapp" name="whatsapp_number" type="text" class="mt-1 block w-full text-xs" x-model="editGuest.whatsapp_number" />
                    </div>

                    <div>
                        <x-input-label for="edit_status" value="Status Undangan" />
                        <select id="edit_status" name="status" class="mt-1 block w-full rounded-md border-gray-300 text-xs shadow-sm" x-model="editGuest.status">
                            <option value="pending">Pending (Belum Dikirim)</option>
                            <option value="sent">Sent (Terkirim)</option>
                            <option value="opened">Opened (Dibuka)</option>
                            <option value="confirmed">Confirmed (Dikonfirmasi / RSVP)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t">
                        <button type="button" @click="editGuest = null" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">
                            Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL IMPORT CSV -->
        <div x-show="showImportModal" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4" x-cloak>
            <div @click.away="showImportModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-sm">Import Tamu dari Berkas CSV</h3>
                    <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('weddings.guests.import', $wedding) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="import_file" value="Pilih Berkas CSV *" />
                        <input type="file" id="import_file" name="file" accept=".csv,.txt" class="mt-1 block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" required />
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl text-[11px] text-gray-500 space-y-1">
                        <div class="font-bold text-gray-700">Format CSV yang didukung:</div>
                        <div>Kolom 1: <code>Nama Tamu</code></div>
                        <div>Kolom 2: <code>Nomor WhatsApp</code> (opsional)</div>
                        <div class="pt-1 text-gray-400">Contoh baris: <br><code>Budi Santoso, 081234567890</code><br><code>Citra Lestari, 089876543210</code></div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t">
                        <button type="button" @click="showImportModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">
                            Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
