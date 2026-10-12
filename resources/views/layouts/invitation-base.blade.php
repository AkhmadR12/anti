<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $wedding->title }} &mdash; The Wedding of {{ $couple->groom_name ?? 'Pria' }} & {{ $couple->bride_name ?? 'Wanita' }}</title>
    
    <!-- Meta SEO & Social Sharing -->
    <meta name="description" content="Undangan Pernikahan Digital {{ $couple->groom_name ?? 'Pria' }} & {{ $couple->bride_name ?? 'Wanita' }}. {{ $settings->opening_text ?? 'Mohon doa restu.' }}">
    <meta property="og:title" content="{{ $wedding->title }}">
    <meta property="og:description" content="The Wedding Celebration of {{ $couple->groom_name ?? 'Pria' }} & {{ $couple->bride_name ?? 'Wanita' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($galleries->isNotEmpty())
        <meta property="og:image" content="{{ str_starts_with($galleries->first()->image_path, 'http') ? $galleries->first()->image_path : asset('storage/' . $galleries->first()->image_path) }}">
    @endif

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@400;600;700&family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & Alpine.js -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dynamic Theme Styling Variables -->
    <style>
        :root {
            --primary-color: {{ $settings->primary_color ?? '#4F46E5' }};
            --secondary-color: {{ $settings->secondary_color ?? '#EC4899' }};
            --font-theme: '{{ $settings->font_family ?? 'Playfair Display' }}', serif;
        }

        .font-heading {
            font-family: var(--font-theme);
        }

        .font-script {
            font-family: 'Great Vibes', cursive;
        }

        .bg-theme-primary {
            background-color: var(--primary-color);
        }

        .text-theme-primary {
            color: var(--primary-color);
        }

        .border-theme-primary {
            border-color: var(--primary-color);
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Hide scrollbar during cover modal */
        body.modal-open {
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 antialiased font-sans selection:bg-rose-500 selection:text-white"
      x-data="{
          isOpened: false,
          isPlaying: false,
          audio: null,
          openInvitation() {
              this.isOpened = true;
              document.body.classList.remove('modal-open');
              if (this.audio) {
                  this.audio.play().then(() => {
                      this.isPlaying = true;
                  }).catch(e => {
                      console.log('Audio autoplay prevented:', e);
                  });
              }
          },
          toggleAudio() {
              if (!this.audio) return;
              if (this.isPlaying) {
                  this.audio.pause();
                  this.isPlaying = false;
              } else {
                  this.audio.play();
                  this.isPlaying = true;
              }
          },
          init() {
              document.body.classList.add('modal-open');
              @if ($settings->music_url)
                  this.audio = new Audio('{{ $settings->music_url }}');
                  this.audio.loop = true;
              @endif
          }
      }">

    <!-- Preview Warning Banner if viewed in admin preview mode -->
    @if ($isPreview ?? false)
        <div class="fixed top-0 inset-x-0 z-50 bg-amber-500 text-white text-xs font-bold py-2 px-4 text-center shadow-md flex items-center justify-between">
            <span class="mx-auto">Mode Preview: Ini adalah tampilan pratinjau undangan untuk Anda sebagai pemilik.</span>
            <a href="{{ route('weddings.edit', $wedding) }}" class="underline bg-amber-600 px-3 py-1 rounded hover:bg-amber-700">Kembali ke Editor</a>
        </div>
    @endif

    <!-- FLOATING MUSIC BUTTON -->
    @if ($settings->music_url)
        <div class="fixed bottom-6 right-6 z-40" x-show="isOpened" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
            <button @click="toggleAudio()" class="w-12 h-12 rounded-full bg-white/90 backdrop-blur-md shadow-xl border border-gray-200 text-gray-800 flex items-center justify-center hover:scale-105 transition" :class="isPlaying ? 'animate-spin' : ''" style="animation-duration: 4s;">
                <template x-if="isPlaying">
                    <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </template>
                <template x-if="!isPlaying">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                    </svg>
                </template>
            </button>
        </div>
    @endif

    <!-- MAIN TEMPLATE CONTENT (RENDERED BY CHILD TEMPLATE) -->
    <div class="min-h-screen">
        @yield('content')
    </div>

    <!-- COUNTDOWN TIMER HELPER SCRIPT -->
    <script>
        function countdownTimer(targetDateStr) {
            return {
                target: new Date(targetDateStr).getTime(),
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
                init() {
                    this.update();
                    setInterval(() => this.update(), 1000);
                },
                update() {
                    const now = new Date().getTime();
                    const distance = this.target - now;
                    if (distance < 0) {
                        this.days = 0;
                        this.hours = 0;
                        this.minutes = 0;
                        this.seconds = 0;
                        return;
                    }
                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                }
            }
        }
    </script>
</body>
</html>
