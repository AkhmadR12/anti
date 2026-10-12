@extends('layouts.invitation-base')

@section('content')
<!-- COVER / ENVELOPE MODAL (FULLSCREEN) -->
<div x-show="!isOpened"
     x-transition:leave="transition ease-in-out duration-700 transform"
     x-transition:leave-start="translate-y-0 opacity-100"
     x-transition:leave-end="-translate-y-full opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900 text-stone-100 px-4 text-center">
    
    <!-- Background overlay image if present -->
    <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('{{ $galleries->first() ? (str_starts_with($galleries->first()->image_path, 'http') ? $galleries->first()->image_path : asset('storage/' . $galleries->first()->image_path)) : 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80' }}')"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-950/70 to-stone-900/60"></div>

    <div class="relative z-10 max-w-md w-full space-y-6 p-6">
        <p class="text-xs uppercase tracking-[0.3em] text-rose-300 font-semibold">The Wedding Celebration of</p>
        
        <h1 class="font-heading text-4xl sm:text-5xl font-bold tracking-wide text-white leading-tight">
            {{ $couple->groom_name ?? 'Budi' }} <span class="font-script text-rose-400 font-normal">&</span> {{ $couple->bride_name ?? 'Siti' }}
        </h1>

        <!-- Personalized Guest Greeting -->
        <div class="py-4 my-2 border-y border-stone-800/80">
            <p class="text-xs text-stone-400 font-light mb-1">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
            <h2 class="text-xl font-bold text-rose-200">
                {{ $guest->name ?? 'Tamu Undangan' }}
            </h2>
            <p class="text-[11px] text-stone-500 mt-1 italic">*Mohon maaf bila ada kesalahan penulisan nama/gelar</p>
        </div>

        <div>
            <button @click="openInvitation()" class="inline-flex items-center gap-2.5 px-8 py-3.5 bg-gradient-to-r from-rose-500 to-amber-600 hover:from-rose-600 hover:to-amber-700 text-white font-semibold text-sm rounded-full shadow-lg hover:shadow-rose-500/20 transform hover:scale-105 transition duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                </svg>
                Buka Undangan
            </button>
        </div>
    </div>
</div>

<!-- INVITATION MAIN CONTAINER (MOBILE FIRST) -->
<div class="max-w-xl mx-auto bg-stone-50 min-h-screen shadow-2xl relative overflow-hidden border-x border-stone-200">

    <!-- HERO SECTION -->
    <section class="relative min-h-[90vh] flex items-center justify-center text-center p-8 bg-stone-900 text-white">
        <div class="absolute inset-0 bg-cover bg-center opacity-40" style="background-image: url('{{ $galleries->first() ? (str_starts_with($galleries->first()->image_path, 'http') ? $galleries->first()->image_path : asset('storage/' . $galleries->first()->image_path)) : 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80' }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-stone-950/60 via-stone-900/40 to-stone-900"></div>

        <div class="relative z-10 space-y-6">
            <p class="text-xs uppercase tracking-[0.3em] text-rose-300">Undangan Pernikahan</p>
            <h2 class="font-heading text-4xl sm:text-5xl font-bold tracking-wide">
                {{ $couple->groom_name ?? 'Budi' }} & {{ $couple->bride_name ?? 'Siti' }}
            </h2>

            @if ($events->isNotEmpty())
                <div class="text-sm font-light text-stone-300 tracking-wider">
                    {{ $events->first()->event_date->format('l, d F Y') }}
                </div>
            @endif

            <!-- COUNTDOWN TIMER -->
            <div x-data="countdownTimer('{{ $targetDate }}')" class="pt-6">
                <div class="grid grid-cols-4 gap-2 max-w-xs mx-auto">
                    <div class="bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/15">
                        <div class="text-2xl font-bold font-heading text-rose-300" x-text="days">0</div>
                        <div class="text-[10px] uppercase text-stone-400">Hari</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/15">
                        <div class="text-2xl font-bold font-heading text-rose-300" x-text="hours">0</div>
                        <div class="text-[10px] uppercase text-stone-400">Jam</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/15">
                        <div class="text-2xl font-bold font-heading text-rose-300" x-text="minutes">0</div>
                        <div class="text-[10px] uppercase text-stone-400">Menit</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/15">
                        <div class="text-2xl font-bold font-heading text-rose-300" x-text="seconds">0</div>
                        <div class="text-[10px] uppercase text-stone-400">Detik</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- QUOTE / OPENING SECTION -->
    <section class="p-8 text-center bg-stone-50 space-y-4 border-b border-stone-200">
        <div class="w-12 h-0.5 bg-rose-400 mx-auto"></div>
        <p class="text-xs sm:text-sm text-stone-600 italic font-serif leading-relaxed px-4">
            "{{ $settings->opening_text ?? 'Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya.' }}"
        </p>
        <div class="w-12 h-0.5 bg-rose-400 mx-auto"></div>
    </section>

    <!-- BRIDE & GROOM SECTION -->
    <section class="p-8 bg-white space-y-8 text-center border-b border-stone-200">
        <div class="space-y-1">
            <p class="text-xs uppercase tracking-widest text-rose-500 font-bold">Mempelai Pernikahan</p>
            <h3 class="font-heading text-2xl font-bold text-stone-800">Assalamu'alaikum Wr. Wb.</h3>
            <p class="text-xs text-stone-500 max-w-sm mx-auto">Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan syukuran pernikahan putra-putri kami:</p>
        </div>

        <!-- Mempelai Pria -->
        <div class="space-y-3">
            @if ($couple->groom_photo)
                <img src="{{ asset('storage/' . $couple->groom_photo) }}" alt="Groom" class="w-28 h-28 rounded-full object-cover mx-auto shadow-md border-2 border-rose-300" />
            @else
                <div class="w-28 h-28 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center mx-auto border-2 border-stone-200 font-heading text-2xl font-bold">
                    {{ substr($couple->groom_name ?? 'P', 0, 1) }}
                </div>
            @endif
            <div>
                <h4 class="font-heading text-xl font-bold text-stone-900">{{ $couple->groom_full_name ?? 'Budi Santoso' }}</h4>
                <p class="text-xs text-stone-500 mt-1">
                    Putra dari Bapak {{ $couple->groom_father_name ?? 'Ayah Pria' }} & Ibu {{ $couple->groom_mother_name ?? 'Ibu Pria' }}
                </p>
            </div>
        </div>

        <div class="font-script text-4xl text-rose-400">&</div>

        <!-- Mempelai Wanita -->
        <div class="space-y-3">
            @if ($couple->bride_photo)
                <img src="{{ asset('storage/' . $couple->bride_photo) }}" alt="Bride" class="w-28 h-28 rounded-full object-cover mx-auto shadow-md border-2 border-rose-300" />
            @else
                <div class="w-28 h-28 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center mx-auto border-2 border-stone-200 font-heading text-2xl font-bold">
                    {{ substr($couple->bride_name ?? 'W', 0, 1) }}
                </div>
            @endif
            <div>
                <h4 class="font-heading text-xl font-bold text-stone-900">{{ $couple->bride_full_name ?? 'Siti Nurhaliza' }}</h4>
                <p class="text-xs text-stone-500 mt-1">
                    Putri dari Bapak {{ $couple->bride_father_name ?? 'Ayah Wanita' }} & Ibu {{ $couple->bride_mother_name ?? 'Ibu Wanita' }}
                </p>
            </div>
        </div>
    </section>

    <!-- WEDDING EVENTS SECTION -->
    <section class="p-8 bg-stone-100/60 space-y-6 border-b border-stone-200">
        <div class="text-center space-y-1">
            <p class="text-xs uppercase tracking-widest text-rose-500 font-bold">Agenda Acara</p>
            <h3 class="font-heading text-2xl font-bold text-stone-800">Waktu & Tempat Pelaksanaan</h3>
        </div>

        <div class="space-y-4">
            @foreach ($events as $event)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-200 text-center space-y-3">
                    <span class="inline-block px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-full uppercase tracking-wider">
                        {{ $event->title }}
                    </span>
                    <div class="font-heading text-lg font-bold text-stone-800">
                        {{ $event->event_date->format('l, d F Y') }}
                    </div>
                    <div class="text-xs text-stone-600">
                        Pukul {{ substr($event->start_time, 0, 5) }} {{ $event->end_time ? '- ' . substr($event->end_time, 0, 5) : 'WIB s/d Selesai' }}
                    </div>
                    <div class="pt-2 border-t border-stone-100">
                        <div class="font-bold text-sm text-stone-900">{{ $event->venue }}</div>
                        <div class="text-xs text-stone-500 mt-0.5">{{ $event->address }}</div>
                    </div>
                    @if ($event->maps_url)
                        <div class="pt-2">
                            <a href="{{ $event->maps_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-stone-900 text-white rounded-xl text-xs font-semibold hover:bg-stone-800 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Buka Petunjuk Lokasi Maps
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <!-- GALLERY SECTION -->
    @if ($galleries->isNotEmpty())
        <section class="p-8 bg-white space-y-6 border-b border-stone-200 text-center">
            <div class="space-y-1">
                <p class="text-xs uppercase tracking-widest text-rose-500 font-bold">Galeri Kami</p>
                <h3 class="font-heading text-2xl font-bold text-stone-800">Momen Bahagia</h3>
            </div>

            <div class="grid grid-cols-2 gap-3" x-data="{ activeImg: null }">
                @foreach ($galleries as $gal)
                    @php
                        $imgPath = str_starts_with($gal->image_path, 'http') ? $gal->image_path : asset('storage/' . $gal->image_path);
                    @endphp
                    <div class="rounded-xl overflow-hidden aspect-square bg-stone-100 cursor-pointer hover:opacity-90 transition shadow-sm" @click="activeImg = '{{ $imgPath }}'">
                        <img src="{{ $imgPath }}" alt="{{ $gal->caption ?? 'Gallery' }}" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                @endforeach

                <!-- Lightbox Modal -->
                <div x-show="activeImg" @click.away="activeImg = null" class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" x-cloak>
                    <div class="relative max-w-lg w-full">
                        <button @click="activeImg = null" class="absolute -top-10 right-0 text-white font-bold text-2xl">&times;</button>
                        <img :src="activeImg" class="w-full max-h-[80vh] object-contain rounded-xl shadow-2xl" />
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- WEDDING GIFTS / REKENING SECTION -->
    @if (($settings->show_gift ?? true) && $gifts->isNotEmpty())
        <section class="p-8 bg-stone-50 space-y-6 border-b border-stone-200 text-center" x-data="{ copiedId: null }">
            <div class="space-y-1">
                <p class="text-xs uppercase tracking-widest text-rose-500 font-bold">Tanda Kasih</p>
                <h3 class="font-heading text-2xl font-bold text-stone-800">Kado Pernikahan</h3>
                <p class="text-xs text-stone-500 max-w-xs mx-auto">Doa restu Anda merupakan karunia terindah bagi kami. Namun jika ingin memberikan tanda kasih secara digital, Anda dapat melalui:</p>
            </div>

            <div class="space-y-4">
                @foreach ($gifts as $gift)
                    <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm space-y-2 text-center">
                        <div class="text-xs font-bold uppercase tracking-wider text-rose-700">{{ $gift->bank_or_wallet_name }}</div>
                        <div class="text-lg font-mono font-bold text-stone-900">{{ $gift->account_number }}</div>
                        <div class="text-xs text-stone-500">a.n {{ $gift->account_holder_name }}</div>
                        <div>
                            <button @click="navigator.clipboard.writeText('{{ $gift->account_number }}'); copiedId = {{ $gift->id }}; setTimeout(() => copiedId = null, 2000)" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-lg transition">
                                <span x-text="copiedId === {{ $gift->id }} ? 'Tersalin!' : 'Salin Rekening'"></span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- RSVP & GUESTBOOK SECTION -->
    @if ($settings->show_rsvp ?? true)
        <section class="p-8 bg-white space-y-6 border-b border-stone-200">
            <div class="text-center space-y-1">
                <p class="text-xs uppercase tracking-widest text-rose-500 font-bold">Konfirmasi Kehadiran</p>
                <h3 class="font-heading text-2xl font-bold text-stone-800">RSVP & Doa Restu</h3>
            </div>

            <form method="POST" action="{{ route('invitation.rsvp', $wedding->slug) }}" class="space-y-4">
                @csrf
                <input type="hidden" name="guest_id" value="{{ $guest->id ?? '' }}">
                <input type="text" name="honeypot_field" class="hidden" style="display:none;" tabindex="-1" autocomplete="off">

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Nama Anda *</label>
                    <input type="text" name="name" value="{{ $guest->name ?? old('name') }}" class="w-full rounded-xl border-stone-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500" placeholder="Nama Anda" required />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Konfirmasi *</label>
                        <select name="attendance" class="w-full rounded-xl border-stone-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500">
                            <option value="attending">Hadir</option>
                            <option value="not_attending">Berhalangan Hadir</option>
                            <option value="tentative">Masih Ragu</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Jumlah Hadir</label>
                        <select name="guest_count" class="w-full rounded-xl border-stone-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500">
                            <option value="1">1 Orang</option>
                            <option value="2">2 Orang</option>
                            <option value="3">3 Orang</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Ucapan & Doa Restu</label>
                    <textarea name="message" rows="3" class="w-full rounded-xl border-stone-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500" placeholder="Tuliskan ucapan dan doa terbaik untuk kedua mempelai..."></textarea>
                </div>

                <div>
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-rose-500 to-amber-600 hover:from-rose-600 hover:to-amber-700 text-white font-bold text-sm rounded-xl shadow-md transition">
                        Kirim Konfirmasi & Ucapan
                    </button>
                </div>
            </form>

            <!-- GUESTBOOK WISH WALL -->
            @if (($settings->show_guestbook ?? true) && $rsvps->isNotEmpty())
                <div class="pt-6 border-t border-stone-100 space-y-3">
                    <h4 class="font-heading text-base font-bold text-stone-800 text-center">Ucapan Doa Sahabat & Keluarga</h4>
                    <div class="max-h-80 overflow-y-auto space-y-3 pr-1">
                        @foreach ($rsvps as $rsvp)
                            <div class="bg-stone-50 p-4 rounded-xl border border-stone-200/70 text-left space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-xs text-stone-900">{{ $rsvp->name }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full {{ $rsvp->attendance === 'attending' ? 'bg-emerald-100 text-emerald-800 font-semibold' : 'bg-stone-200 text-stone-600' }}">
                                        {{ $rsvp->attendance === 'attending' ? 'Hadir' : 'Tidak Hadir' }}
                                    </span>
                                </div>
                                <p class="text-xs text-stone-600 font-light leading-relaxed">{{ $rsvp->message }}</p>
                                <span class="text-[10px] text-stone-400 block">{{ $rsvp->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    @endif

    <!-- CLOSING SECTION -->
    <footer class="p-8 bg-stone-900 text-white text-center space-y-4">
        <p class="text-xs text-stone-400 italic max-w-sm mx-auto">
            "{{ $settings->closing_text ?? 'Merupakan kehormatan bagi kami apabila Anda berkenan hadir dan mendoakan kami.' }}"
        </p>

        <h3 class="font-heading text-3xl font-bold text-rose-300">
            {{ $couple->groom_name ?? 'Budi' }} & {{ $couple->bride_name ?? 'Siti' }}
        </h3>

        <div class="pt-6 border-t border-stone-800 text-[11px] text-stone-500">
            Dibuat dengan segenap cinta &bull; Undangan Digital NikahLink
        </div>
    </footer>

</div>
@endsection
