<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        Rekapitulasi RSVP & Buku Tamu
                    </h2>
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold bg-rose-50 text-rose-700">
                        {{ $wedding->title }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Pantau konfirmasi kehadiran para tamu, estimasi jumlah porsi katering, dan kelola ucapan doa restu.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('weddings.guests.index', $wedding) }}" class="px-3.5 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition">
                    Daftar Tamu
                </a>
                <a href="{{ route('weddings.edit', $wedding) }}" class="px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    Editor Undangan
                </a>
                <a href="{{ route('dashboard') }}" class="px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl flex items-center justify-between shadow-sm">
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="text-2xl font-bold text-gray-900">{{ $metrics['total_responses'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Total Respon</div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="text-2xl font-bold text-emerald-600">{{ $metrics['attending_count'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Konfirmasi Hadir</div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-indigo-100 bg-indigo-50/20 shadow-sm">
                    <div class="text-2xl font-bold text-indigo-600">{{ $metrics['total_pax'] }} <span class="text-xs font-normal text-indigo-400">Pax</span></div>
                    <div class="text-xs text-indigo-700 font-medium">Estimasi Tamu Hadir</div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="text-2xl font-bold text-rose-600">{{ $metrics['not_attending_count'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Berhalangan Hadir</div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="text-2xl font-bold text-amber-600">{{ $metrics['tentative_count'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Masih Ragu</div>
                </div>
            </div>

            <!-- Toolbar & Filter -->
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search Form -->
                <form method="GET" action="{{ route('weddings.rsvps.index', $wedding) }}" class="w-full md:w-80 flex items-center gap-2">
                    @if ($filter)
                        <input type="hidden" name="attendance" value="{{ $filter }}">
                    @endif
                    <x-text-input name="q" type="text" value="{{ $search ?? '' }}" placeholder="Cari nama atau pesan..." class="text-xs w-full py-2" />
                    <button type="submit" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                        Cari
                    </button>
                    @if ($search)
                        <a href="{{ route('weddings.rsvps.index', $wedding) }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                    @endif
                </form>

                <!-- Attendance Filters & Export Button -->
                <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
                    <div class="flex items-center gap-1 bg-gray-50 p-1 rounded-xl border border-gray-200 text-[11px] font-semibold">
                        <a href="{{ route('weddings.rsvps.index', $wedding) }}" class="px-2.5 py-1 rounded-lg transition {{ empty($filter) ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-900' }}">
                            Semua
                        </a>
                        <a href="{{ route('weddings.rsvps.index', [$wedding, 'attendance' => 'attending']) }}" class="px-2.5 py-1 rounded-lg transition {{ $filter === 'attending' ? 'bg-emerald-600 text-white shadow-sm' : 'text-emerald-700 hover:text-emerald-900' }}">
                            Hadir
                        </a>
                        <a href="{{ route('weddings.rsvps.index', [$wedding, 'attendance' => 'not_attending']) }}" class="px-2.5 py-1 rounded-lg transition {{ $filter === 'not_attending' ? 'bg-rose-600 text-white shadow-sm' : 'text-rose-700 hover:text-rose-900' }}">
                            Tidak Hadir
                        </a>
                        <a href="{{ route('weddings.rsvps.index', [$wedding, 'attendance' => 'tentative']) }}" class="px-2.5 py-1 rounded-lg transition {{ $filter === 'tentative' ? 'bg-amber-500 text-white shadow-sm' : 'text-amber-700 hover:text-amber-900' }}">
                            Ragu
                        </a>
                    </div>

                    <a href="{{ route('weddings.rsvps.export', $wedding) }}" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>

            <!-- List of RSVPs & Wishes -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                @if ($rsvps->isEmpty())
                    <div class="text-center py-16 px-4">
                        <p class="text-sm font-semibold text-gray-700 mb-1">Belum ada konfirmasi RSVP atau ucapan</p>
                        <p class="text-xs text-gray-400">Respon dari tamu yang mengisi formulir di undangan akan otomatis muncul di sini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500 font-semibold uppercase tracking-wider text-[10px]">
                                    <th class="p-4">Nama Tamu</th>
                                    <th class="p-4">Kehadiran</th>
                                    <th class="p-4">Porsi / Pax</th>
                                    <th class="p-4">Doa & Ucapan</th>
                                    <th class="p-4">Waktu</th>
                                    <th class="p-4 text-right">Moderasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($rsvps as $r)
                                    <tr class="hover:bg-gray-50/60 transition">
                                        <!-- Nama -->
                                        <td class="p-4 font-bold text-gray-900">
                                            {{ $r->name }}
                                            @if ($r->guest)
                                                <span class="block text-[10px] text-indigo-600 font-normal">Tamu Terdaftar</span>
                                            @endif
                                        </td>

                                        <!-- Status Kehadiran -->
                                        <td class="p-4">
                                            @if ($r->attendance === 'attending')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                    Hadir
                                                </span>
                                            @elseif ($r->attendance === 'not_attending')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                                    Tidak Hadir
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                                    Ragu-ragu
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Pax -->
                                        <td class="p-4 text-gray-700 font-semibold">
                                            {{ $r->attendance === 'attending' ? $r->guest_count . ' Pax' : '-' }}
                                        </td>

                                        <!-- Pesan Ucapan -->
                                        <td class="p-4 max-w-sm">
                                            <p class="text-gray-700 text-xs leading-relaxed italic line-clamp-3">
                                                {{ $r->message ? '"' . $r->message . '"' : '-' }}
                                            </p>
                                        </td>

                                        <!-- Waktu -->
                                        <td class="p-4 text-gray-400 text-[11px] whitespace-nowrap">
                                            {{ $r->created_at->diffForHumans() }}
                                        </td>

                                        <!-- Aksi Hapus / Moderasi -->
                                        <td class="p-4 text-right">
                                            <form method="POST" action="{{ route('weddings.rsvps.destroy', [$wedding, $r]) }}" onsubmit="return confirm('Hapus ucapan/respon ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-[11px]">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-t border-gray-100">
                        {{ $rsvps->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
