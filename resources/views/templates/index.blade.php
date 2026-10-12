<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Desain Template Undangan &mdash; NikahLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans">

    <!-- Top Navbar -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="font-bold text-xl text-indigo-600 tracking-tight flex items-center gap-1.5">
                    <span>💍</span> NikahLink
                </a>
                <span class="text-xs text-gray-400">/</span>
                <span class="text-xs font-semibold text-gray-600">Katalog Template</span>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-gray-700 hover:text-indigo-600 px-3 py-2">
                        Dashboard
                    </a>
                    <a href="{{ route('weddings.create') }}" class="text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl shadow-sm transition">
                        + Buat Undangan
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-gray-700 hover:text-indigo-600 px-3 py-2">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl shadow-sm transition">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Header -->
    <section class="py-12 px-4 sm:px-6 lg:px-8 text-center max-w-4xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
            Koleksi Desain Eksklusif
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3 mb-3">
            Pilih Template Undangan Pernikahan Anda
        </h1>
        <p class="text-sm sm:text-base text-gray-500 max-w-2xl mx-auto">
            Semua template dirancang responsive mobile-first, elegan, hemat kuota, dan dilengkapi musik latar, RSVP otomatis, serta peta Google Maps terintegrasi.
        </p>
    </section>

    <!-- Flash Alert -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
        @if (session('status'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif
    </div>

    <!-- Template Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($templates as $tmpl)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between">
                    
                    <div>
                        <!-- Thumbnail Preview Area -->
                        <div class="relative h-48 bg-gradient-to-br from-indigo-100 via-rose-50 to-amber-100 flex items-center justify-center p-6 border-b border-gray-100">
                            <div class="text-center space-y-1">
                                <span class="text-3xl">💐</span>
                                <h3 class="text-xl font-bold text-gray-800">{{ $tmpl->name }}</h3>
                                <p class="text-xs text-gray-500 font-mono">slug: {{ $tmpl->slug }}</p>
                            </div>

                            <!-- Premium / Free Badge -->
                            <div class="absolute top-4 right-4">
                                @if ($tmpl->is_premium)
                                    <span class="px-2.5 py-1 bg-amber-500 text-white text-[11px] font-bold rounded-full uppercase tracking-wider shadow">
                                        ★ Premium
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-emerald-500 text-white text-[11px] font-bold rounded-full uppercase tracking-wider shadow">
                                        Free
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="p-6 space-y-2">
                            <h4 class="font-bold text-lg text-gray-900">{{ $tmpl->name }}</h4>
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $tmpl->description }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-6 pt-0 border-t border-gray-50 flex items-center gap-3 mt-4">
                        <!-- Live Demo Button -->
                        <a href="{{ route('templates.demo', $tmpl->slug) }}" target="_blank" class="flex-1 text-center py-2.5 px-3 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold rounded-xl transition">
                            Lihat Live Demo ↗
                        </a>

                        <!-- Use Template Action -->
                        @auth
                            @if ($userWeddings->isNotEmpty())
                                <div x-data="{ openDropdown: false }" class="relative flex-1">
                                    <button @click="openDropdown = !openDropdown" class="w-full py-2.5 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                        Gunakan &darr;
                                    </button>

                                    <!-- Dropdown to select which wedding receives this template -->
                                    <div x-show="openDropdown" @click.away="openDropdown = false" class="absolute right-0 bottom-full mb-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 z-20 space-y-1" x-cloak>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase px-2 py-1">Pilih Undangan:</div>
                                        @foreach ($userWeddings as $w)
                                            <form method="POST" action="{{ route('templates.select', $tmpl) }}">
                                                @csrf
                                                <input type="hidden" name="wedding_id" value="{{ $w->id }}">
                                                <button type="submit" class="w-full text-left px-2.5 py-1.5 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition font-medium flex items-center justify-between">
                                                    <span class="truncate">{{ $w->title }}</span>
                                                    @if ($w->template_id == $tmpl->id)
                                                        <span class="text-[10px] text-emerald-600 font-bold">Aktif</span>
                                                    @endif
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('weddings.create') }}" class="flex-1 text-center py-2.5 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                    Pakai Sekarang
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="flex-1 text-center py-2.5 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                Pilih Template
                            </a>
                        @endauth
                    </div>

                </div>
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 bg-white py-8 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} NikahLink &bull; Platform Undangan Pernikahan Digital Multi-User
    </footer>

</body>
</html>
