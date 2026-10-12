# NikahLink — Multi-User Digital Wedding Invitation Platform

[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)](https://alpinejs.dev)
[![Tests Passing](https://img.shields.io/badge/Tests-56%20Passed%20(181%20Assertions)-success?style=for-the-badge)](https://phpunit.de)

**NikahLink** adalah platform SaaS (Software as a Service) modern berbasis **Laravel 11** untuk membuat, mengelola, dan membagikan undangan pernikahan digital yang elegan, responsif, dan kaya fitur dalam hitungan menit.

Aplikasi dirancang dengan standar **Enterprise Software Architecture**, **Strict Multi-Tenant Data Isolation (Anti-IDOR)**, **Modular Template Engine**, dan keamanan tingkat tinggi.

---

## 🌟 Fitur Unggulan

### 1. 🛡️ Arsitektur Multi-User & Isolasi Data Ketat
- Setiap data undangan (`weddings`, `couples`, `events`, `galleries`, `guests`, `rsvps`, dll.) terikat langsung dengan `user_id` pemiliknya.
- Dilindungi oleh **Laravel Authorization Policies** (`WeddingPolicy`) di setiap controller dan Route Model Binding.
- **Anti-IDOR (Insecure Direct Object Reference)**: Mencegah pengguna memanipulasi atau melihat data pernikahan milik pengguna lain melalui URL parameter.

### 2. ⚡ 7-Step Interactive Wedding Wizard
Wizard interaktif bertahap bertenaga **Alpine.js**:
1. **Data Pengantin**: Nama lengkap, panggilan, orang tua, dan foto kedua mempelai.
2. **Data Acara**: Multi-event dinamis (Akad Nikah, Resepsi, dll.) dengan tanggal, jam, venue, dan tautan Google Maps.
3. **Galeri Foto**: Multi-upload foto kenangan dengan validasi MIME dan ukuran gambar.
4. **Cerita Cinta (Love Story)**: Timeline perjalanan cinta kedua mempelai.
5. **Hadiah Digital**: Rekening bank dan QRIS untuk amplop digital.
6. **Pengaturan & Musik**: Teks pembuka/penutup, lagu latar otomatis, dan pemilihan template.
7. **Review & Publish**: Pratinjau draf dan publikasi instan dengan slug URL unik.

### 3. 🎨 Sistem Template Modular & Reusable
- Arsitektur view modular di `resources/views/templates/{slug}/invitation.blade.php`.
- Menggunakan base layout terpadu (`layouts/invitation-base.blade.php`) dengan:
  - **Amplop Digital Interaktif** (Opening Cover Modal).
  - **Floating Audio Player** (Autoplay saat amplop dibuka + tombol kontrol Pause/Play).
  - **Hitung Mundur Dinamis (Countdown Timer)**.
  - **OpenGraph & SEO Meta Tags** untuk preview WhatsApp & sosial media.
- **3 Template Bawaan Siap Pakai**:
  - `elegant`: Tema mewah klasik bernuansa Rose Gold & aksen Serif.
  - `floral`: Nuansa pastel romantis dengan elemen watercolour botani.
  - `minimalist`: Gaya kontemporer bersih bernuansa Sage & Zinc modern.

### 4. 💬 WhatsApp 1-Click Sharing & Manajemen Tamu
- **Normalisasi Nomor WhatsApp**: Format nomor lokal (`0812...`) otomatis dinormalisasi menjadi standar internasional (`62812...`).
- **URL Undangan Personal**: Mendukung URL unik per tamu (`/invitation/{weddingSlug}/{guestSlug}`) yang otomatis menampilkan nama tamu di cover undangan.
- **Deep Linking WhatsApp**: Tombol 1-klik untuk langsung mengirim pesan WhatsApp berformat rapi dan personal.
- **Import Tamu via CSV**: Unggah puluhan daftar tamu sekaligus via file CSV.

### 5. 💌 RSVP & Buku Tamu Real-Time Anti-Spam
- Konfirmasi kehadiran (Hadir, Tidak Hadir, Masih Ragu) dan ucapan doa restu langsung dari halaman undangan.
- **Proteksi Spam & Bot**:
  - **Honeypot Field** (`honeypot_field` tersembunyi yang mendeteksi bot otomatis).
  - **Rate Limiting** via Laravel middleware `throttle:10,1`.
- **Dashboard Moderasi RSVP**: Filter status kehadiran, hapus komentar spam, dan **Ekspor RSVP ke CSV**.

---

## 🏗️ Struktur Skema Database

```text
users
  └── weddings
        ├── couples (hasOne)
        ├── wedding_events (hasMany)
        ├── galleries (hasMany)
        ├── wedding_settings (hasOne)
        ├── love_stories (hasMany)
        ├── wedding_gifts (hasMany)
        ├── guests (hasMany)
        └── rsvps (hasMany)
```

Tabel `templates` bersifat global dan reusable untuk semua wedding.

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### Prasyarat:
- PHP >= 8.2 (dengan ekstensi: `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL / MariaDB (XAMPP / Standalone)

### Langkah Instalasi:

1. **Clone Repository**:
   ```bash
   git clone https://github.com/AkhmadR12/anti.git nikahlink
   cd nikahlink
   ```

2. **Instal Dependensi PHP**:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**:
   Salin berkas konfigurasi lingkungan:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan koneksi database Anda:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=wedding_platform
   DB_USERNAME=root
   DB_PASSWORD=
   DB_COLLATION=utf8mb4_unicode_ci
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi & Seeder Demo**:
   Pastikan database `wedding_platform` sudah dibuat di MySQL/MariaDB, lalu jalankan:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Buat Symlink Storage Publik**:
   ```bash
   php artisan storage:link
   ```

7. **Instal & Build Aset Frontend**:
   ```bash
   npm install
   npm run build
   ```

8. **Jalankan Local Development Server**:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di: `http://127.0.0.1:8000`

---

## 🔑 Akun Demo Bawaan

Setelah menjalankan `php artisan migrate:fresh --seed`, tersedia akun demo untuk pengujian:

| Role / Akun | Email | Password | Slug Undangan Demo |
| :--- | :--- | :--- | :--- |
| **User A (Pemilik)** | `demo@example.com` | `password` | `/invitation/budi-siti` |
| **User B (User Lain)** | `userb@example.com` | `password` | `/invitation/andi-sarah` |

> **Catatan Multi-Tenancy**: Anda dapat login sebagai User B dan mencoba mengakses URL `/weddings/1/edit` (milik User A). Sistem akan mengembalikan response **403 Forbidden** secara konsisten.

---

## 🧪 Pengujian Otomatis (Automated Tests)

Platform ini dilengkapi dengan **56 Unit & Feature Tests** menyeluruh yang mencakup:
- Authentication & Registrasi (Breeze)
- WhatsApp Normalizer Unit Test
- Multi-tenancy & Isolation Verification
- 7-Step Wizard Submission & Form Validation
- Template Switching & Template Fallback Engine
- Guest Management & CSV Import
- WhatsApp Deep Link Generation
- Public Invitation & Personalized Guest View
- Honeypot Anti-Spam & RSVP Throttle
- RSVP CSV Export & Moderation

Jalankan test suite menggunakan perintah:
```bash
php artisan test
```

Hasil:
```text
Tests:    56 passed (181 assertions)
Duration: ~4.2s
```

---

## 🔒 Standar Keamanan & Best Practices

1. **Strict IDOR Mitigation**: Validasi ganda di level Route Model Binding dan Laravel Policy (`$this->authorize('update', $wedding)`).
2. **CSRF Protection**: Semua form dilengkapi token `@csrf`.
3. **Form Request Validation**: Semua input pengguna difilter dan divalidasi ketat melalui Dedicated Form Requests (`app/Http/Requests`).
4. **Honeypot & Rate Limiting**: Endpoint publik buku tamu dilindungi dari injeksi spam bot.
5. **Database Indexing**: Kunci asing dan kolom pencarian utama (`user_id`, `wedding_id`, `slug`, `status`) telah diindeks untuk performa tinggi pada skala jutaan baris data.

---

## 📄 Lisensi

Platform ini dirilis di bawah lisensi [MIT License](LICENSE).
