@extends('layouts.invitation-base')

@section('content')
<!-- COVER MODAL -->
<div x-show="!isOpened"
     x-transition:leave="transition ease-in-out duration-700 transform"
     x-transition:leave-start="scale-100 opacity-100"
     x-transition:leave-end="scale-90 opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-emerald-950 text-emerald-50 px-4 text-center">
    
    <div class="absolute inset-0 bg-cover bg-center opacity-25" style="background-image: url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=1200&q=80')"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-emerald-950 via-emerald-950/80 to-teal-900/60"></div>

    <div class="relative z-10 max-w-md w-full space-y-6 p-6">
        <div class="inline-block px-4 py-1 rounded-full border border-emerald-400/40 text-xs font-semibold text-emerald-300 uppercase tracking-widest">
            Wedding Invitation
        </div>

        <h1 class="font-heading text-4xl sm:text-5xl font-bold tracking-wide text-white">
            {{ $couple->groom_name ?? 'Budi' }} <span class="font-script text-emerald-400">&</span> {{ $couple->bride_name ?? 'Siti' }}
        </h1>

        <div class="py-4 my-2 border-y border-emerald-800/80">
            <p class="text-xs text-emerald-300/80 font-light mb-1">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
            <h2 class="text-xl font-bold text-white tracking-wide">
                {{ $guest->name ?? 'Tamu Undangan' }}
            </h2>
        </div>

        <div>
            <button @click="openInvitation()" class="inline-flex items-center gap-2 px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-full shadow-lg shadow-emerald-900/30 transform hover:scale-105 transition duration-300">
                <span>🌿</span> Buka Undangan
            </button>
        </div>
    </div>
</div>

<!-- INVITATION CONTAINER -->
<div class="max-w-xl mx-auto bg-stone-50 min-h-screen shadow-2xl relative overflow-hidden border-x border-emerald-100">

    <!-- HERO SECTION -->
    <section class="relative min-h-[85vh] flex items-center justify-center text-center p-8 bg-emerald-900 text-white">
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=800&q=80')"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-emerald-950/70 via-emerald-900/50 to-emerald-900"></div>

        <div class="relative z-10 space-y-6">
            <span class="text-xs uppercase tracking-[0.25em] text-emerald-300 font-semibold">The Wedding of</span>
            <h2 class="font-heading text-4xl sm:text-5xl font-bold">
                {{ $couple->groom_name ?? 'Budi' }} & {{ $couple->bride_name ?? 'Siti' }}
            </h2>

            @if ($events->isNotEmpty())
                <div class="text-sm font-light text-emerald-200 tracking-wider">
                    {{ $events->first()->event_date->format('l, d F Y') }}
                </div>
            @endif

            <!-- COUNTDOWN TIMER -->
            <div x-data="countdownTimer('{{ $targetDate }}')" class="pt-6">
                <div class="grid grid-cols-4 gap-2 max-w-xs mx-auto">
                    <div class="bg-emerald-800/40 backdrop-blur-md p-3 rounded-2xl border border-emerald-400/20">
                        <div class="text-2xl font-bold font-heading text-emerald-300" x-text="days">0</div>
                        <div class="text-[10px] uppercase text-emerald-200/70">Hari</div>
                    </div>
                    <div class="bg-emerald-800/40 backdrop-blur-md p-3 rounded-2xl border border-emerald-400/20">
                        <div class="text-2xl font-bold font-heading text-emerald-300" x-text="hours">0</div>
                        <div class="text-[10px] uppercase text-emerald-200/70">Jam</div>
                    </div>
                    <div class="bg-emerald-800/40 backdrop-blur-md p-3 rounded-2xl border border-emerald-400/20">
                        <div class="text-2xl font-bold font-heading text-emerald-300" x-text="minutes">0</div>
                        <div class="text-[10px] uppercase text-emerald-200/70">Menit</div>
                    </div>
                    <div class="bg-emerald-800/40 backdrop-blur-md p-3 rounded-2xl border border-emerald-400/20">
                        <div class="text-2xl font-bold font-heading text-emerald-300" x-text="seconds">0</div>
                        <div class="text-[10px] uppercase text-emerald-200/70">Detik</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- QUOTES -->
    <section class="p-8 text-center bg-emerald-50/50 space-y-3 border-b border-emerald-100">
        <p class="text-xs sm:text-sm text-emerald-800/80 italic font-serif leading-relaxed px-4">
            "{{ $settings->opening_text ?? 'Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan.' }}"
        </p>
    </section>

    <!-- BRIDE & GROOM -->
    <section class="p-8 bg-white space-y-8 text-center border-b border-emerald-100">
        <div class="space-y-1">
            <span class="text-xs uppercase tracking-widest text-emerald-600 font-bold">Mempelai Bahagia</span>
            <h3 class="font-heading text-2xl font-bold text-gray-800">Kedua Pengantin</h3>
        </div>

        <!-- Pria -->
        <div class="space-y-3">
            @if ($couple->groom_photo)
                <img src="{{ asset('storage/' . $couple->groom_photo) }}" alt="Groom" class="w-28 h-28 rounded-full object-cover mx-auto shadow-md border-2 border-emerald-300" />
            @else
                <div class="w-28 h-28 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto border-2 border-emerald-200 font-heading text-2xl font-bold">
                    {{ substr($couple->groom_name ?? 'P', 0, 1) }}
                </div>
            @endif
            <div>
                <h4 class="font-heading text-xl font-bold text-gray-900">{{ $couple->groom_full_name ?? 'Budi Santoso' }}</h4>
                <p class="text-xs text-gray-500 mt-1">
                    Putra dari Bapak {{ $couple->groom_father_name ?? 'Ayah Pria' }} & Ibu {{ $couple->groom_mother_name ?? 'Ibu Pria' }}
                </p>
            </div>
        </div>

        <div class="font-script text-4xl text-emerald-500">&</div>

        <!-- Wanita -->
        <div class="space-y-3">
            @if ($couple->bride_photo)
                <img src="{{ asset('storage/' . $couple->bride_photo) }}" alt="Bride" class="w-28 h-28 rounded-full object-cover mx-auto shadow-md border-2 border-emerald-300" />
            @else
                <div class="w-28 h-28 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto border-2 border-emerald-200 font-heading text-2xl font-bold">
                    {{ substr($couple->bride_name ?? 'W', 0, 1) }}
                </div>
            @endif
            <div>
                <h4 class="font-heading text-xl font-bold text-gray-900">{{ $couple->bride_full_name ?? 'Siti Nurhaliza' }}</h4>
                <p class="text-xs text-gray-500 mt-1">
                    Putri dari Bapak {{ $couple->bride_father_name ?? 'Ayah Wanita' }} & Ibu {{ $couple->bride_mother_name ?? 'Ibu Wanita' }}
                </p>
            </div>
        </div>
    </section>

    <!-- EVENTS -->
    <section class="p-8 bg-emerald-50/30 space-y-6 border-b border-emerald-100">
        <div class="text-center space-y-1">
            <span class="text-xs uppercase tracking-widest text-emerald-600 font-bold">Waktu & Tempat</span>
            <h3 class="font-heading text-2xl font-bold text-gray-800">Rangkaian Acara</h3>
        </div>

        <div class="space-y-4">
            @foreach ($events as $event)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100 text-center space-y-3">
                    <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full uppercase tracking-wider">
                        {{ $event->title }}
                    </span>
                    <div class="font-heading text-lg font-bold text-gray-800">
                        {{ $event->event_date->format('l, d F Y') }}
                    </div>
                    <div class="text-xs text-gray-600">
                        {{ substr($event->start_time, 0, 5) }} {{ $event->end_time ? '- ' . substr($event->end_time, 0, 5) : 'WIB s/d Selesai' }}
                    </div>
                    <div class="pt-2 border-t border-emerald-50">
                        <div class="font-bold text-sm text-gray-900">{{ $event->venue }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $event->address }}</div>
                    </div>
                    @if ($event->maps_url)
                        <div class="pt-2">
                            <a href="{{ $event->maps_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-700 text-white rounded-xl text-xs font-semibold hover:bg-emerald-800 transition">
                                Petunjuk Google Maps
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <!-- GALLERY -->
    @if ($galleries->isNotEmpty())
        <section class="p-8 bg-white space-y-6 border-b border-emerald-100 text-center">
            <div class="space-y-1">
                <span class="text-xs uppercase tracking-widest text-emerald-600 font-bold">Dokumentasi</span>
                <h3 class="font-heading text-2xl font-bold text-gray-800">Galeri Foto</h3>
            </div>

            <div class="grid grid-cols-2 gap-3" x-data="{ activeImg: null }">
                @foreach ($galleries as $gal)
                    @php
                        $imgPath = str_starts_with($gal->image_path, 'http') ? $gal->image_path : asset('storage/' . $gal->image_path);
                    @endphp
                    <div class="rounded-2xl overflow-hidden aspect-square bg-emerald-50 cursor-pointer hover:opacity-90 transition shadow-sm" @click="activeImg = '{{ $imgPath }}'">
                        <img src="{{ $imgPath }}" alt="{{ $gal->caption ?? 'Gallery' }}" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                @endforeach

                <div x-show="activeImg" @click.away="activeImg = null" class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" x-cloak>
                    <div class="relative max-w-lg w-full">
                        <button @click="activeImg = null" class="absolute -top-10 right-0 text-white font-bold text-2xl">&times;</button>
                        <img :src="activeImg" class="w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl" />
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- RSVP & WISHES -->
    @if ($settings->show_rsvp ?? true)
        <section class="p-8 bg-emerald-50/40 space-y-6 border-b border-emerald-100">
            <div class="text-center space-y-1">
                <span class="text-xs uppercase tracking-widest text-emerald-600 font-bold">Buku Tamu</span>
                <h3 class="font-heading text-2xl font-bold text-gray-800">Konfirmasi RSVP & Doa</h3>
            </div>

            <form method="POST" action="{{ route('invitation.rsvp', $wedding->slug) }}" class="space-y-4 bg-white p-6 rounded-2xl border border-emerald-100 shadow-sm">
                @csrf
                <input type="hidden" name="guest_id" value="{{ $guest->id ?? '' }}">
                <input type="text" name="honeypot_field" class="hidden" style="display:none;" tabindex="-1" autocomplete="off">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ $guest->name ?? old('name') }}" class="w-full rounded-xl border-emerald-200 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Nama Anda" required />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kehadiran *</label>
                        <select name="attendance" class="w-full rounded-xl border-emerald-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="attending">Hadir</option>
                            <option value="not_attending">Berhalangan</option>
                            <option value="tentative">Ragu-ragu</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Jumlah</label>
                        <select name="guest_count" class="w-full rounded-xl border-emerald-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="1">1 Orang</option>
                            <option value="2">2 Orang</option>
                            <option value="3">3 Orang</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Doa & Harapan</label>
                    <textarea name="message" rows="3" class="w-full rounded-xl border-emerald-200 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Berikan ucapan untuk mempelai..."></textarea>
                </div>

                <div>
                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition">
                        Kirim Konfirmasi Kehadiran
                    </button>
                </div>
            </form>

            @if (($settings->show_guestbook ?? true) && $rsvps->isNotEmpty())
                <div class="pt-4 space-y-3">
                    <h4 class="font-heading text-base font-bold text-gray-800 text-center">Doa Restu Tamu</h4>
                    <div class="max-h-80 overflow-y-auto space-y-3 pr-1">
                        @foreach ($rsvps as $rsvp)
                            <div class="bg-white p-4 rounded-xl border border-emerald-100 text-left space-y-1 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-xs text-gray-900">{{ $rsvp->name }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full {{ $rsvp->attendance === 'attending' ? 'bg-emerald-100 text-emerald-800 font-semibold' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $rsvp->attendance === 'attending' ? 'Hadir' : 'Tidak Hadir' }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 font-light leading-relaxed">{{ $rsvp->message }}</p>
                                <span class="text-[10px] text-gray-400 block">{{ $rsvp->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    @endif

    <!-- CLOSING -->
    <footer class="p-8 bg-emerald-950 text-white text-center space-y-4">
        <p class="text-xs text-emerald-300/80 italic max-w-sm mx-auto">
            "{{ $settings->closing_text ?? 'Terima kasih atas doa dan restu yang tulus.' }}"
        </p>

        <h3 class="font-heading text-3xl font-bold text-emerald-300">
            {{ $couple->groom_name ?? 'Budi' }} & {{ $couple->bride_name ?? 'Siti' }}
        </h3>

        <div class="pt-6 border-t border-emerald-900 text-[11px] text-emerald-400/60">
            Undangan Pernikahan Digital NikahLink
        </div>
    </footer>

</div>
@endsection
