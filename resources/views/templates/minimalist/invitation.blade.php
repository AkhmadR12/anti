@extends('layouts.invitation-base')

@section('content')
<!-- COVER MODAL -->
<div x-show="!isOpened"
     x-transition:leave="transition ease-in-out duration-600 transform"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900 text-zinc-100 px-4 text-center">
    
    <div class="relative z-10 max-w-sm w-full space-y-8 p-8 border border-zinc-700/60 bg-zinc-800/80 backdrop-blur-md rounded-3xl shadow-2xl">
        <div class="text-[11px] font-mono tracking-widest uppercase text-zinc-400">
            Official Invitation
        </div>

        <div class="space-y-2">
            <h1 class="text-3xl sm:text-4xl font-light tracking-wide text-zinc-100 font-sans">
                {{ $couple->groom_name ?? 'Budi' }} <span class="text-zinc-500 font-serif italic">&</span> {{ $couple->bride_name ?? 'Siti' }}
            </h1>
            <p class="text-xs text-zinc-400 font-mono">
                {{ $events->first() ? $events->first()->event_date->format('d . m . Y') : date('d . m . Y') }}
            </p>
        </div>

        <div class="pt-4 border-t border-zinc-700/50">
            <p class="text-[11px] text-zinc-400 font-light mb-1">Kepada Rekan & Sahabat:</p>
            <h2 class="text-lg font-semibold text-zinc-200">
                {{ $guest->name ?? 'Tamu Undangan' }}
            </h2>
        </div>

        <div>
            <button @click="openInvitation()" class="w-full py-3.5 bg-zinc-100 hover:bg-white text-zinc-900 font-medium text-xs rounded-2xl shadow transition tracking-wider uppercase">
                Buka Undangan
            </button>
        </div>
    </div>
</div>

<!-- INVITATION CONTAINER -->
<div class="max-w-xl mx-auto bg-white min-h-screen shadow-xl relative border-x border-zinc-200">

    <!-- HERO SECTION -->
    <section class="min-h-[80vh] flex items-center justify-center text-center p-8 bg-zinc-900 text-white relative">
        <div class="space-y-6">
            <div class="text-xs font-mono uppercase tracking-[0.3em] text-zinc-400">The Wedding of</div>
            <h2 class="text-4xl sm:text-5xl font-light tracking-wider">
                {{ $couple->groom_name ?? 'Budi' }} <span class="font-serif italic font-normal text-zinc-400">&</span> {{ $couple->bride_name ?? 'Siti' }}
            </h2>

            @if ($events->isNotEmpty())
                <div class="text-xs font-mono text-zinc-400 tracking-widest">
                    {{ $events->first()->event_date->format('d F Y') }}
                </div>
            @endif

            <!-- COUNTDOWN TIMER -->
            <div x-data="countdownTimer('{{ $targetDate }}')" class="pt-6">
                <div class="grid grid-cols-4 gap-2 max-w-xs mx-auto text-center font-mono">
                    <div class="bg-zinc-800/80 p-3 rounded-xl border border-zinc-700">
                        <div class="text-xl font-bold text-zinc-200" x-text="days">0</div>
                        <div class="text-[9px] uppercase text-zinc-400">Hari</div>
                    </div>
                    <div class="bg-zinc-800/80 p-3 rounded-xl border border-zinc-700">
                        <div class="text-xl font-bold text-zinc-200" x-text="hours">0</div>
                        <div class="text-[9px] uppercase text-zinc-400">Jam</div>
                    </div>
                    <div class="bg-zinc-800/80 p-3 rounded-xl border border-zinc-700">
                        <div class="text-xl font-bold text-zinc-200" x-text="minutes">0</div>
                        <div class="text-[9px] uppercase text-zinc-400">Menit</div>
                    </div>
                    <div class="bg-zinc-800/80 p-3 rounded-xl border border-zinc-700">
                        <div class="text-xl font-bold text-zinc-200" x-text="seconds">0</div>
                        <div class="text-[9px] uppercase text-zinc-400">Detik</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- QUOTES -->
    <section class="p-8 text-center bg-zinc-50 border-b border-zinc-100">
        <p class="text-xs text-zinc-600 font-serif italic max-w-md mx-auto leading-relaxed">
            "{{ $settings->opening_text ?? 'Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu.' }}"
        </p>
    </section>

    <!-- BRIDE & GROOM -->
    <section class="p-8 space-y-8 text-center border-b border-zinc-100">
        <div class="space-y-1">
            <span class="text-[11px] uppercase tracking-widest text-zinc-400 font-mono">Our Story</span>
            <h3 class="text-2xl font-light text-zinc-900">Kedua Mempelai</h3>
        </div>

        <!-- Pria -->
        <div class="space-y-3">
            @if ($couple->groom_photo)
                <img src="{{ asset('storage/' . $couple->groom_photo) }}" alt="Groom" class="w-24 h-24 rounded-2xl object-cover mx-auto shadow-sm grayscale hover:grayscale-0 transition" />
            @else
                <div class="w-24 h-24 rounded-2xl bg-zinc-100 text-zinc-500 flex items-center justify-center mx-auto text-xl font-bold">
                    {{ substr($couple->groom_name ?? 'P', 0, 1) }}
                </div>
            @endif
            <div>
                <h4 class="text-lg font-medium text-zinc-900">{{ $couple->groom_full_name ?? 'Budi Santoso' }}</h4>
                <p class="text-xs text-zinc-500 mt-0.5">
                    Putra dari Bpk. {{ $couple->groom_father_name ?? 'Ayah Pria' }} & Ibu {{ $couple->groom_mother_name ?? 'Ibu Pria' }}
                </p>
            </div>
        </div>

        <div class="text-zinc-300 font-light">&mdash; with &mdash;</div>

        <!-- Wanita -->
        <div class="space-y-3">
            @if ($couple->bride_photo)
                <img src="{{ asset('storage/' . $couple->bride_photo) }}" alt="Bride" class="w-24 h-24 rounded-2xl object-cover mx-auto shadow-sm grayscale hover:grayscale-0 transition" />
            @else
                <div class="w-24 h-24 rounded-2xl bg-zinc-100 text-zinc-500 flex items-center justify-center mx-auto text-xl font-bold">
                    {{ substr($couple->bride_name ?? 'W', 0, 1) }}
                </div>
            @endif
            <div>
                <h4 class="text-lg font-medium text-zinc-900">{{ $couple->bride_full_name ?? 'Siti Nurhaliza' }}</h4>
                <p class="text-xs text-zinc-500 mt-0.5">
                    Putri dari Bpk. {{ $couple->bride_father_name ?? 'Ayah Wanita' }} & Ibu {{ $couple->bride_mother_name ?? 'Ibu Wanita' }}
                </p>
            </div>
        </div>
    </section>

    <!-- EVENTS -->
    <section class="p-8 bg-zinc-50/60 space-y-6 border-b border-zinc-100">
        <div class="text-center space-y-1">
            <span class="text-[11px] uppercase tracking-widest text-zinc-400 font-mono">Schedule</span>
            <h3 class="text-2xl font-light text-zinc-900">Agenda Pernikahan</h3>
        </div>

        <div class="space-y-4">
            @foreach ($events as $event)
                <div class="bg-white p-6 rounded-2xl border border-zinc-200/80 shadow-sm text-center space-y-2">
                    <span class="text-xs font-mono uppercase text-zinc-400 block tracking-widest">{{ $event->title }}</span>
                    <div class="text-base font-medium text-zinc-900">{{ $event->event_date->format('l, d F Y') }}</div>
                    <div class="text-xs text-zinc-500">{{ substr($event->start_time, 0, 5) }} {{ $event->end_time ? '- ' . substr($event->end_time, 0, 5) : 'WIB' }}</div>
                    <div class="pt-2 border-t border-zinc-100 text-xs text-zinc-700">
                        <span class="font-semibold">{{ $event->venue }}</span> &bull; {{ $event->address }}
                    </div>
                    @if ($event->maps_url)
                        <div class="pt-2">
                            <a href="{{ $event->maps_url }}" target="_blank" class="inline-block text-xs font-mono text-zinc-900 underline hover:text-zinc-600">
                                View Location on Google Maps &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <!-- GALLERIES -->
    @if ($galleries->isNotEmpty())
        <section class="p-8 space-y-6 border-b border-zinc-100 text-center">
            <div class="space-y-1">
                <span class="text-[11px] uppercase tracking-widest text-zinc-400 font-mono">Moments</span>
                <h3 class="text-2xl font-light text-zinc-900">Galeri Foto</h3>
            </div>

            <div class="grid grid-cols-2 gap-3" x-data="{ activeImg: null }">
                @foreach ($galleries as $gal)
                    @php
                        $imgPath = str_starts_with($gal->image_path, 'http') ? $gal->image_path : asset('storage/' . $gal->image_path);
                    @endphp
                    <div class="rounded-xl overflow-hidden aspect-square bg-zinc-100 cursor-pointer hover:opacity-90 transition" @click="activeImg = '{{ $imgPath }}'">
                        <img src="{{ $imgPath }}" alt="{{ $gal->caption ?? 'Gallery' }}" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                @endforeach

                <div x-show="activeImg" @click.away="activeImg = null" class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4" x-cloak>
                    <div class="relative max-w-lg w-full">
                        <button @click="activeImg = null" class="absolute -top-10 right-0 text-white font-bold text-2xl">&times;</button>
                        <img :src="activeImg" class="w-full max-h-[80vh] object-contain rounded-xl" />
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- RSVP -->
    @if ($settings->show_rsvp ?? true)
        <section class="p-8 bg-zinc-50 space-y-6 border-b border-zinc-100">
            <div class="text-center space-y-1">
                <span class="text-[11px] uppercase tracking-widest text-zinc-400 font-mono">Attendance</span>
                <h3 class="text-2xl font-light text-zinc-900">RSVP & Wishes</h3>
            </div>

            <form method="POST" action="{{ route('invitation.rsvp', $wedding->slug) }}" class="space-y-4 bg-white p-6 rounded-2xl border border-zinc-200">
                @csrf
                <input type="hidden" name="guest_id" value="{{ $guest->id ?? '' }}">
                <input type="text" name="honeypot_field" class="hidden" style="display:none;" tabindex="-1" autocomplete="off">

                <div>
                    <label class="block text-xs font-medium text-zinc-700 mb-1">Nama *</label>
                    <input type="text" name="name" value="{{ $guest->name ?? old('name') }}" class="w-full rounded-xl border-zinc-300 text-sm focus:border-zinc-800 focus:ring-zinc-800" placeholder="Nama Anda" required />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Status Kehadiran</label>
                        <select name="attendance" class="w-full rounded-xl border-zinc-300 text-sm focus:border-zinc-800 focus:ring-zinc-800">
                            <option value="attending">Hadir</option>
                            <option value="not_attending">Tidak Hadir</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-700 mb-1">Jumlah</label>
                        <select name="guest_count" class="w-full rounded-xl border-zinc-300 text-sm focus:border-zinc-800 focus:ring-zinc-800">
                            <option value="1">1 Orang</option>
                            <option value="2">2 Orang</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-700 mb-1">Pesan / Doa</label>
                    <textarea name="message" rows="3" class="w-full rounded-xl border-zinc-300 text-sm focus:border-zinc-800 focus:ring-zinc-800" placeholder="Tuliskan ucapan Anda..."></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-zinc-900 hover:bg-black text-white text-xs font-medium uppercase tracking-wider rounded-xl transition">
                    Submit RSVP
                </button>
            </form>

            @if (($settings->show_guestbook ?? true) && $rsvps->isNotEmpty())
                <div class="pt-4 space-y-3">
                    <h4 class="text-sm font-medium text-zinc-800 text-center">Ucapan Selamat</h4>
                    <div class="max-h-80 overflow-y-auto space-y-3 pr-1">
                        @foreach ($rsvps as $rsvp)
                            <div class="bg-white p-4 rounded-xl border border-zinc-200 text-left space-y-1">
                                <div class="flex items-center justify-between text-xs font-medium">
                                    <span class="text-zinc-900">{{ $rsvp->name }}</span>
                                    <span class="text-[10px] text-zinc-500 font-mono">{{ $rsvp->attendance === 'attending' ? 'Hadir' : 'Absen' }}</span>
                                </div>
                                <p class="text-xs text-zinc-600 font-light leading-relaxed">{{ $rsvp->message }}</p>
                                <span class="text-[10px] text-zinc-400 font-mono">{{ $rsvp->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    @endif

    <!-- CLOSING -->
    <footer class="p-8 bg-zinc-900 text-white text-center space-y-4">
        <h3 class="text-2xl font-light text-zinc-200">
            {{ $couple->groom_name ?? 'Budi' }} & {{ $couple->bride_name ?? 'Siti' }}
        </h3>
        <p class="text-xs text-zinc-400 font-light max-w-sm mx-auto">
            "{{ $settings->closing_text ?? 'Terima kasih atas doa dan restu yang tulus.' }}"
        </p>
        <div class="pt-6 border-t border-zinc-800 text-[10px] text-zinc-500 font-mono">
            NikahLink &bull; Digital Wedding Experience
        </div>
    </footer>

</div>
@endsection
