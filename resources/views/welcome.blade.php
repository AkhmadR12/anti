<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NikahLink — Platform Undangan Pernikahan Digital Elegan & Modern</title>
    <meta name="description" content="Buat undangan pernikahan digital elegan, mobile-friendly, dan praktis hanya dalam beberapa menit. Dilengkapi RSVP real-time, integrasi WhatsApp, musik latar, dan amplop digital.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Playfair+Display:ital,wght@0,600;1,400&display=swap" rel="stylesheet">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js fallback -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .font-serif-c { font-family: 'Cinzel', serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-sans-c { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="font-sans-c antialiased bg-slate-50 text-slate-800 selection:bg-rose-500 selection:text-white" x-data="{ mobileMenu: false }">

    <!-- Navbar -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/85 border-b border-rose-100/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-600 to-amber-500 flex items-center justify-center text-white shadow-md shadow-rose-500/20 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-2xl font-bold tracking-tight text-slate-900 group-hover:text-rose-600 transition-colors">Nikah<span class="text-rose-600">Link</span></span>
                            <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold -mt-1">Digital Invitation</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-8 text-sm font-semibold text-slate-600">
                    <a href="#fitur" class="hover:text-rose-600 transition-colors">Fitur Utama</a>
                    <a href="#template" class="hover:text-rose-600 transition-colors">Koleksi Template</a>
                    <a href="#cara-kerja" class="hover:text-rose-600 transition-colors">Cara Kerja</a>
                    <a href="#harga" class="hover:text-rose-600 transition-colors">Paket & Harga</a>
                    <a href="#faq" class="hover:text-rose-600 transition-colors">FAQ</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 shadow-md shadow-slate-900/10 transition">
                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-rose-600 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-rose-600 to-rose-500 text-white text-sm font-semibold hover:from-rose-700 hover:to-rose-600 shadow-md shadow-rose-500/25 hover:shadow-lg transition">
                            <span>Buat Undangan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenu = !mobileMenu" type="button" class="text-slate-600 hover:text-slate-900 p-2 focus:outline-none">
                        <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenu" x-cloak @click.away="mobileMenu = false" class="md:hidden bg-white border-b border-slate-200 px-4 pt-3 pb-6 space-y-3">
            <a href="#fitur" @click="mobileMenu = false" class="block py-2 text-base font-medium text-slate-700 hover:text-rose-600">Fitur Utama</a>
            <a href="#template" @click="mobileMenu = false" class="block py-2 text-base font-medium text-slate-700 hover:text-rose-600">Koleksi Template</a>
            <a href="#cara-kerja" @click="mobileMenu = false" class="block py-2 text-base font-medium text-slate-700 hover:text-rose-600">Cara Kerja</a>
            <a href="#harga" @click="mobileMenu = false" class="block py-2 text-base font-medium text-slate-700 hover:text-rose-600">Paket & Harga</a>
            <a href="#faq" @click="mobileMenu = false" class="block py-2 text-base font-medium text-slate-700 hover:text-rose-600">FAQ</a>
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full text-center py-2.5 rounded-xl bg-slate-900 text-white font-semibold">Ke Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl border border-slate-200 text-slate-800 font-semibold">Masuk</a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-xl bg-rose-600 text-white font-semibold">Buat Undangan Gratis</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-12 pb-24 lg:pt-20 lg:pb-32 bg-gradient-to-b from-white via-rose-50/40 to-slate-50">
        <!-- Background Ornaments -->
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-gradient-to-tr from-rose-200/40 to-amber-200/30 blur-3xl rounded-full pointer-events-none -z-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Hero Text -->
                <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-100/80 border border-rose-200/60 text-rose-700 text-xs sm:text-sm font-semibold tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        Platform Undangan Pernikahan Digital Multi-User Modern
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15]">
                        Bagikan Momen Bahagiamu dengan Undangan <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-amber-600">Digital Elegan</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto lg:mx-0 font-normal leading-relaxed">
                        NikahLink memudahkan pasangan calon pengantin membuat undangan online estetik dalam hitungan menit. Dilengkapi RSVP real-time, musik latar otomatis, dan generator link personal WhatsApp 1-klik.
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 text-white font-bold shadow-lg shadow-rose-600/25 hover:from-rose-700 hover:to-rose-600 hover:scale-[1.02] active:scale-[0.98] transition">
                            <span>Mulai Buat Undangan Sekarang</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('templates.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-4 rounded-xl border border-slate-300 bg-white text-slate-800 font-semibold hover:bg-slate-50 hover:border-slate-400 transition">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>Lihat Demo Template</span>
                        </a>
                    </div>

                    <!-- Trust Stats -->
                    <div class="pt-6 border-t border-slate-200/80 grid grid-cols-3 gap-4 text-center lg:text-left">
                        <div>
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">100%</p>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium">Mobile Responsive</p>
                        </div>
                        <div>
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">Instant</p>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium">WhatsApp Deep Link</p>
                        </div>
                        <div>
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">Aman</p>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium">Data Terisolasi</p>
                        </div>
                    </div>
                </div>

                <!-- Right Hero Mockup Card -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative w-full max-w-sm sm:max-w-md">
                        <!-- Floating Glass Badges -->
                        <div class="absolute -top-4 -left-6 z-20 hidden sm:flex items-center gap-3 bg-white/95 backdrop-blur-md p-3 rounded-2xl shadow-xl border border-rose-100">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                ✓
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">RSVP Masuk Real-Time</p>
                                <p class="text-[11px] text-slate-500">2 tamu baru mengonfirmasi</p>
                            </div>
                        </div>

                        <div class="absolute -bottom-6 -right-6 z-20 hidden sm:flex items-center gap-3 bg-white/95 backdrop-blur-md p-3.5 rounded-2xl shadow-xl border border-rose-100">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                                🎵
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Floating Music Player</p>
                                <p class="text-[11px] text-slate-500">Audio autoplay halus</p>
                            </div>
                        </div>

                        <!-- Phone Frame Mockup -->
                        <div class="relative bg-slate-900 p-3 sm:p-4 rounded-[40px] shadow-2xl border-4 border-slate-800">
                            <div class="w-32 h-4 bg-slate-800 rounded-full mx-auto mb-3"></div>
                            
                            <div class="bg-gradient-to-b from-stone-900 to-stone-950 rounded-[32px] overflow-hidden text-center text-white p-6 relative border border-amber-900/30">
                                <div class="text-[10px] uppercase tracking-widest text-amber-300 font-serif mb-2">The Wedding of</div>
                                <h3 class="text-2xl font-serif font-bold text-amber-100 tracking-wider">Budi & Siti</h3>
                                <p class="text-xs text-amber-200/70 mt-1">Sabtu, 12 Desember 2026</p>

                                <!-- Mockup Avatar -->
                                <div class="my-6 relative w-36 h-36 mx-auto rounded-full border-2 border-amber-400/40 p-1">
                                    <div class="w-full h-full rounded-full bg-gradient-to-tr from-rose-900 to-amber-800 flex items-center justify-center overflow-hidden">
                                        <svg class="w-16 h-16 text-amber-200/40" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </div>
                                    <div class="absolute bottom-0 right-1 bg-amber-500 text-slate-900 text-[10px] font-bold px-2 py-0.5 rounded-full shadow">
                                        VIP
                                    </div>
                                </div>

                                <!-- Countdown Box Mockup -->
                                <div class="grid grid-cols-4 gap-2 mb-6">
                                    <div class="bg-stone-800/80 rounded-lg p-2 border border-amber-400/10">
                                        <span class="block text-base font-bold text-amber-300">62</span>
                                        <span class="text-[9px] text-stone-400">Hari</span>
                                    </div>
                                    <div class="bg-stone-800/80 rounded-lg p-2 border border-amber-400/10">
                                        <span class="block text-base font-bold text-amber-300">14</span>
                                        <span class="text-[9px] text-stone-400">Jam</span>
                                    </div>
                                    <div class="bg-stone-800/80 rounded-lg p-2 border border-amber-400/10">
                                        <span class="block text-base font-bold text-amber-300">30</span>
                                        <span class="text-[9px] text-stone-400">Menit</span>
                                    </div>
                                    <div class="bg-stone-800/80 rounded-lg p-2 border border-amber-400/10">
                                        <span class="block text-base font-bold text-amber-300">45</span>
                                        <span class="text-[9px] text-stone-400">Detik</span>
                                    </div>
                                </div>

                                <a href="{{ url('/invitation/budi-siti') }}" target="_blank" class="block w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-stone-950 font-bold text-xs tracking-wider uppercase shadow hover:brightness-110 transition">
                                    Buka Undangan Live Demo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Template Showcase Section -->
    <section id="template" class="py-24 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-rose-600">Desain Pilihan</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                    Template Eksklusif Bernuansa Elegan
                </h2>
                <p class="text-slate-600 text-base sm:text-lg">
                    Tiap template dirancang responsif, dilengkapi animasi amplop interaktif, palet warna berkelas, dan layout terstruktur untuk menyajikan kisah bahagiamu.
                </p>
            </div>

            <!-- Template Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Template 1: Elegant -->
                <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-64 bg-gradient-to-tr from-stone-900 to-stone-800 p-6 flex flex-col items-center justify-center text-center relative overflow-hidden">
                        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#d4af37_1px,transparent_1px)] [background-size:16px_16px]"></div>
                        <span class="text-amber-400 text-xs tracking-widest uppercase font-serif mb-2">Serif Luxe</span>
                        <h3 class="text-2xl font-serif text-amber-100 font-bold">Elegant Rose Gold</h3>
                        <p class="text-xs text-amber-200/80 mt-2 max-w-xs">Nuansa royal klasik berhiaskan border emas dan ornamen anggun.</p>
                        <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 text-xs font-medium border border-amber-400/30">
                            ★ Paling Populer
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-6">
                        <div>
                            <h4 class="font-bold text-slate-900 text-lg">Template Elegant</h4>
                            <p class="text-sm text-slate-600 mt-1">Cocok untuk pernikahan bertema megah, resepsi ballroom, dan nuansa adat modern.</p>
                        </div>
                        <div class="flex items-center gap-3 pt-2">
                            <a href="{{ route('templates.demo', 'elegant') }}" target="_blank" class="flex-1 text-center py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                                Live Preview
                            </a>
                            <a href="{{ route('register') }}" class="flex-1 text-center py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                                Pilih Template
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Template 2: Floral -->
                <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-64 bg-gradient-to-tr from-rose-100 to-amber-50 p-6 flex flex-col items-center justify-center text-center relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-rose-300/30 rounded-full blur-xl"></div>
                        <span class="text-rose-600 text-xs tracking-widest uppercase font-serif mb-2">Botanical Pastel</span>
                        <h3 class="text-2xl font-serif text-slate-800 font-bold">Floral Romance</h3>
                        <p class="text-xs text-slate-600 mt-2 max-w-xs">Aksen bunga lembut watercolor dengan atmosfer manis dan romantis.</p>
                        <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-200/60 text-rose-700 text-xs font-medium">
                            🌸 Favorit Calon Pengantin
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-6">
                        <div>
                            <h4 class="font-bold text-slate-900 text-lg">Template Floral</h4>
                            <p class="text-sm text-slate-600 mt-1">Sangat pas untuk konsep garden party, intimate outdoor, dan rustic wedding.</p>
                        </div>
                        <div class="flex items-center gap-3 pt-2">
                            <a href="{{ route('templates.demo', 'floral') }}" target="_blank" class="flex-1 text-center py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                                Live Preview
                            </a>
                            <a href="{{ route('register') }}" class="flex-1 text-center py-2.5 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-700 transition">
                                Pilih Template
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Template 3: Minimalist -->
                <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-64 bg-gradient-to-tr from-slate-900 to-emerald-950 p-6 flex flex-col items-center justify-center text-center relative overflow-hidden">
                        <span class="text-emerald-400 text-xs tracking-widest uppercase font-mono mb-2">Clean Aesthetic</span>
                        <h3 class="text-2xl font-serif text-emerald-100 font-bold">Minimalist Sage</h3>
                        <p class="text-xs text-emerald-200/80 mt-2 max-w-xs">Gaya kontemporer bersih, tipografi modern, dan fokus pada detail cerita.</p>
                        <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-medium border border-emerald-400/30">
                            🌿 Trend Terkini
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-6">
                        <div>
                            <h4 class="font-bold text-slate-900 text-lg">Template Minimalist</h4>
                            <p class="text-sm text-slate-600 mt-1">Pilihan tepat bagi pasangan yang menyukai estetika simpel, modern, dan elegan.</p>
                        </div>
                        <div class="flex items-center gap-3 pt-2">
                            <a href="{{ route('templates.demo', 'minimalist') }}" target="_blank" class="flex-1 text-center py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                                Live Preview
                            </a>
                            <a href="{{ route('register') }}" class="flex-1 text-center py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                                Pilih Template
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('templates.index') }}" class="inline-flex items-center gap-2 text-rose-600 font-bold hover:text-rose-700 transition">
                    <span>Lihat Semua Koleksi Template Kami</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section id="fitur" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-rose-600">Fitur Lengkap</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                    Segala Kebutuhan Undangan Digital dalam Satu Tempat
                </h2>
                <p class="text-slate-600 text-base sm:text-lg">
                    Dirancang dengan arsitektur SaaS yang andal, aman, dan memprioritaskan kenyamanan calon mempelai serta kemudahan para tamu undangan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Wizard Cepat 7-Langkah</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Form bertahap interaktif yang praktis. Simpan draf kapan saja tanpa khawatir kehilangan data saat berpindah langkah.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">WhatsApp 1-Click Sharing</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Buat link personal khusus nama tamu secara instan dan kirim pesan undangan WhatsApp yang rapi langsung dari dashboard.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">RSVP & Ucapan Real-Time</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Ketahui estimasi kehadiran tamu secara akurat. Dilengkapi sistem moderasi, proteksi honeypot anti-spam, dan export CSV.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Floating Music & Countdown</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Musik latar romantis yang mulai berputar saat amplop dibuka dengan kontrol pause/play, serta hitung mundur waktu akad.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Amplop Digital & QRIS</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Tampilkan nomor rekening bank dan QRIS agar kerabat yang berhalangan hadir tetap dapat mengirimkan tanda kasih dan kado.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Multi-User & Keamanan Ketat</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Isolasi data 100% aman berdasar User ID dengan perlindungan Laravel Authorization Policy dari bahaya IDOR URL.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="cara-kerja" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-20">
                <span class="text-xs font-bold uppercase tracking-widest text-rose-600">Alur Mudah</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                    3 Langkah Cepat Siap Disebar
                </h2>
                <p class="text-slate-600 text-base sm:text-lg">
                    Tanpa perlu keahlian teknis atau coding. Cukup ikuti panduan dan undanganmu langsung online.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Step 1 -->
                <div class="text-center space-y-4 relative">
                    <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 border-2 border-rose-200 flex items-center justify-center mx-auto text-2xl font-extrabold shadow-sm">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Daftar Akun Gratis</h3>
                    <p class="text-sm text-slate-600 max-w-xs mx-auto">
                        Cukup isi nama, email, nomor WhatsApp, dan kata sandi. Akun langsung aktif seketika.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center space-y-4 relative">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 border-2 border-amber-200 flex items-center justify-center mx-auto text-2xl font-extrabold shadow-sm">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Isi Data & Unggah Foto</h3>
                    <p class="text-sm text-slate-600 max-w-xs mx-auto">
                        Lengkapi profil mempelai, jadwal acara akad & resepsi, foto galeri, serta pilih template favoritmu.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center space-y-4 relative">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 border-2 border-emerald-200 flex items-center justify-center mx-auto text-2xl font-extrabold shadow-sm">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Publish & Kirim WhatsApp</h3>
                    <p class="text-sm text-slate-600 max-w-xs mx-auto">
                        Terbitkan undangan, input nama tamu, dan klik tombol bagikan ke WhatsApp untuk menyapa para kerabat tercinta.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="py-24 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-rose-600">Investasi Terjangkau</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                    Pilihan Paket Sesuai Kebutuhanmu
                </h2>
                <p class="text-slate-600 text-base sm:text-lg">
                    Harga transparan tanpa biaya tersembunyi. Aktif selamanya untuk mengabadikan momen terindah.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                <!-- Plan 1 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 flex flex-col justify-between shadow-sm">
                    <div class="space-y-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Free Trial</span>
                        <h3 class="text-2xl font-bold text-slate-900">Starter</h3>
                        <div class="pt-2">
                            <span class="text-4xl font-extrabold text-slate-900">Rp 0</span>
                            <span class="text-xs text-slate-500">/ selamanya</span>
                        </div>
                        <p class="text-sm text-slate-600">Cocok untuk mencoba fitur dan membuat draf undangan.</p>
                        
                        <ul class="pt-4 space-y-3 text-sm text-slate-600 border-t border-slate-100">
                            <li class="flex items-center gap-2">✓ 1 Undangan Digital</li>
                            <li class="flex items-center gap-2">✓ Pilihan Template Standar</li>
                            <li class="flex items-center gap-2">✓ Galeri Foto Hingga 5 Foto</li>
                            <li class="flex items-center gap-2">✓ Konfirmasi RSVP Tamu</li>
                            <li class="flex items-center gap-2 text-slate-400">✕ Custom Musik Latar</li>
                            <li class="flex items-center gap-2 text-slate-400">✕ Amplop Digital & QRIS</li>
                        </ul>
                    </div>
                    <div class="pt-8">
                        <a href="{{ route('register') }}" class="block w-full text-center py-3 rounded-xl border border-slate-300 text-slate-700 font-bold text-sm hover:bg-slate-50 transition">
                            Coba Gratis
                        </a>
                    </div>
                </div>

                <!-- Plan 2: Pro (Best Value) -->
                <div class="bg-slate-900 rounded-3xl p-8 border-2 border-rose-500 flex flex-col justify-between shadow-2xl relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-rose-600 to-amber-500 text-white text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full shadow-md">
                        Paling Rekomendasi
                    </div>
                    <div class="space-y-4 text-white">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-400">Pasangan Bahagia</span>
                        <h3 class="text-2xl font-bold">Premium Pro</h3>
                        <div class="pt-2">
                            <span class="text-4xl font-extrabold text-rose-400">Rp 99.000</span>
                            <span class="text-xs text-slate-400">/ sekali bayar</span>
                        </div>
                        <p class="text-sm text-slate-300">Semua fitur lengkap tanpa batasan untuk hari pernikahan impian.</p>
                        
                        <ul class="pt-4 space-y-3 text-sm text-slate-300 border-t border-slate-800">
                            <li class="flex items-center gap-2 text-white">✓ <strong>Semua Template Eksklusif</strong></li>
                            <li class="flex items-center gap-2">✓ Galeri Foto Tanpa Batas</li>
                            <li class="flex items-center gap-2">✓ Background Music & Floating Player</li>
                            <li class="flex items-center gap-2">✓ Amplop Digital & QRIS Terintegrasi</li>
                            <li class="flex items-center gap-2">✓ Generator Link WhatsApp Otomatis</li>
                            <li class="flex items-center gap-2">✓ Manajemen Tamu & Import CSV</li>
                            <li class="flex items-center gap-2">✓ Masa Aktif Selamanya</li>
                        </ul>
                    </div>
                    <div class="pt-8">
                        <a href="{{ route('register') }}" class="block w-full text-center py-3.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 text-white font-bold text-sm shadow-lg shadow-rose-600/30 hover:from-rose-700 hover:to-rose-600 transition">
                            Pilih Paket Pro
                        </a>
                    </div>
                </div>

                <!-- Plan 3: Wedding Organizer -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 flex flex-col justify-between shadow-sm">
                    <div class="space-y-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Agensi / WO</span>
                        <h3 class="text-2xl font-bold text-slate-900">Organizer</h3>
                        <div class="pt-2">
                            <span class="text-4xl font-extrabold text-slate-900">Rp 299.000</span>
                            <span class="text-xs text-slate-500">/ bulan</span>
                        </div>
                        <p class="text-sm text-slate-600">Dirancang khusus untuk Wedding Planner & Organizer multi-klien.</p>
                        
                        <ul class="pt-4 space-y-3 text-sm text-slate-600 border-t border-slate-100">
                            <li class="flex items-center gap-2">✓ Multi Undangan Klien Tak Terbatas</li>
                            <li class="flex items-center gap-2">✓ White-Label Branding Sendiri</li>
                            <li class="flex items-center gap-2">✓ Ekspor Data Tamu & RSVP CSV</li>
                            <li class="flex items-center gap-2">✓ Prioritas Dukungan Teknis 24/7</li>
                            <li class="flex items-center gap-2">✓ Semua Fitur Premium Termasuk</li>
                        </ul>
                    </div>
                    <div class="pt-8">
                        <a href="{{ route('register') }}" class="block w-full text-center py-3 rounded-xl border border-slate-300 text-slate-700 font-bold text-sm hover:bg-slate-50 transition">
                            Hubungi Tim Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 bg-white" x-data="{ activeFaq: null }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-rose-600">Pertanyaan Umum</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                    Sering Ditanyakan (FAQ)
                </h2>
                <p class="text-slate-600 text-base sm:text-lg">
                    Jawaban seputar pembuatan dan pengoperasian undangan pernikahan digital NikahLink.
                </p>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="border border-slate-200 rounded-2xl p-5 cursor-pointer hover:border-slate-300 transition" @click="activeFaq = (activeFaq === 1 ? null : 1)">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-900 text-base sm:text-lg">Apakah undangan bisa diedit kembali setelah diterbitkan (publish)?</h4>
                        <span class="text-slate-400 font-bold text-xl" x-text="activeFaq === 1 ? '−' : '+'"></span>
                    </div>
                    <div x-show="activeFaq === 1" x-cloak class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Ya, tentu saja! Anda dapat memperbarui jadwal acara, mengganti foto galeri, mengubah lagu latar, atau mengganti template kapan pun melalui dashboard tanpa mengubah tautan undangan yang sudah dibagikan.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border border-slate-200 rounded-2xl p-5 cursor-pointer hover:border-slate-300 transition" @click="activeFaq = (activeFaq === 2 ? null : 2)">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-900 text-base sm:text-lg">Bagaimana cara mengirim undangan dengan nama tamu personal lewat WhatsApp?</h4>
                        <span class="text-slate-400 font-bold text-xl" x-text="activeFaq === 2 ? '−' : '+'"></span>
                    </div>
                    <div x-show="activeFaq === 2" x-cloak class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Di menu Manajemen Tamu pada dashboard, Anda cukup memasukkan nama tamu (atau import dari file Excel/CSV). Sistem akan otomatis menghasilkan tombol WhatsApp dengan teks undangan sopan yang mencantumkan nama penerima secara personal.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border border-slate-200 rounded-2xl p-5 cursor-pointer hover:border-slate-300 transition" @click="activeFaq = (activeFaq === 3 ? null : 3)">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-900 text-base sm:text-lg">Apakah data dan privasi pernikahan kami aman?</h4>
                        <span class="text-slate-400 font-bold text-xl" x-text="activeFaq === 3 ? '−' : '+'"></span>
                    </div>
                    <div x-show="activeFaq === 3" x-cloak class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Sangat aman. Platform kami menggunakan arsitektur multi-tenant berbasis Laravel Policy. Setiap pengguna hanya memiliki akses ke undangannya sendiri, serta form buku tamu dilengkapi dengan perlindungan anti-spam honeypot dan rate limiter ketat.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="border border-slate-200 rounded-2xl p-5 cursor-pointer hover:border-slate-300 transition" @click="activeFaq = (activeFaq === 4 ? null : 4)">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-900 text-base sm:text-lg">Berapa lama masa aktif undangan digital setelah dibuat?</h4>
                        <span class="text-slate-400 font-bold text-xl" x-text="activeFaq === 4 ? '−' : '+'"></span>
                    </div>
                    <div x-show="activeFaq === 4" x-cloak class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Undangan Anda akan aktif selamanya di platform kami, sehingga dapat dijadikan kenang-kenangan digital yang indah bersama pasangan dan keluarga tercinta hingga masa depan.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Banner -->
    <section class="py-20 bg-gradient-to-r from-rose-600 via-rose-500 to-amber-600 text-white relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-white/10 blur-2xl"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6 relative z-10">
            <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
                Wujudkan Undangan Digital Impianmu Sekarang
            </h2>
            <p class="text-rose-100 text-base sm:text-lg max-w-2xl mx-auto">
                Bergabunglah bersama ribuan pasangan yang telah mempercayakan momen sakral mereka di NikahLink. Cepat, mudah, dan penuh kehangatan.
            </p>
            <div class="pt-4">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-xl bg-white text-rose-600 font-extrabold text-base shadow-xl hover:bg-rose-50 hover:scale-105 active:scale-95 transition">
                    <span>Mulai Buat Undangan — 100% Gratis</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <!-- Brand col -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-500 to-amber-400 flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">Nikah<span class="text-rose-500">Link</span></span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Platform SaaS Undangan Pernikahan Digital Multi-User modern berbasis Laravel 11, Tailwind CSS & MariaDB.
                    </p>
                </div>

                <!-- Navigation -->
                <div>
                    <h4 class="text-white text-sm font-semibold mb-4 uppercase tracking-wider">Navigasi</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#fitur" class="hover:text-white transition">Fitur Utama</a></li>
                        <li><a href="#template" class="hover:text-white transition">Koleksi Template</a></li>
                        <li><a href="#cara-kerja" class="hover:text-white transition">Cara Kerja</a></li>
                        <li><a href="#harga" class="hover:text-white transition">Paket & Harga</a></li>
                        <li><a href="#faq" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>

                <!-- Templates -->
                <div>
                    <h4 class="text-white text-sm font-semibold mb-4 uppercase tracking-wider">Template Populer</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('templates.demo', 'elegant') }}" target="_blank" class="hover:text-white transition">Elegant Rose Gold</a></li>
                        <li><a href="{{ route('templates.demo', 'floral') }}" target="_blank" class="hover:text-white transition">Floral Botanical</a></li>
                        <li><a href="{{ route('templates.demo', 'minimalist') }}" target="_blank" class="hover:text-white transition">Minimalist Sage</a></li>
                        <li><a href="{{ route('templates.index') }}" class="hover:text-white transition">Katalog Lengkap</a></li>
                    </ul>
                </div>

                <!-- Tech & Auth -->
                <div>
                    <h4 class="text-white text-sm font-semibold mb-4 uppercase tracking-wider">Akses Pengguna</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk Akun</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar Pengguna Baru</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Dashboard Undangan</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 text-center sm:flex sm:justify-between sm:items-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} NikahLink. Hak Cipta Dilindungi Undang-Undang.</p>
                <p class="mt-2 sm:mt-0">Ditenagai oleh Laravel 11 • Tailwind CSS • Alpine.js</p>
            </div>
        </div>
    </footer>

</body>
</html>
