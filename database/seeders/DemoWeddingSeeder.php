<?php

namespace Database\Seeders;

use App\Models\Couple;
use App\Models\Gallery;
use App\Models\Guest;
use App\Models\LoveStory;
use App\Models\Rsvp;
use App\Models\Template;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use App\Models\WeddingGift;
use App\Models\WeddingSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoWeddingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or retrieve demo user
        $user = User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'whatsapp_number' => '6281234567890',
                'email_verified_at' => now(),
            ]
        );

        // Also create a second user for testing isolation
        $userB = User::updateOrCreate(
            ['email' => 'userb@example.com'],
            [
                'name' => 'Andi Pratama',
                'password' => Hash::make('password'),
                'whatsapp_number' => '6289876543210',
                'email_verified_at' => now(),
            ]
        );

        $template = Template::where('slug', 'elegant')->first() ?? Template::first();

        // 2. Create demo wedding for user A
        $wedding = Wedding::updateOrCreate(
            ['slug' => 'budi-siti'],
            [
                'user_id' => $user->id,
                'template_id' => $template->id,
                'title' => 'Pernikahan Budi & Siti',
                'status' => 'published',
                'published_at' => now(),
            ]
        );

        // 3. Create couple data
        Couple::updateOrCreate(
            ['wedding_id' => $wedding->id],
            [
                'groom_name' => 'Budi',
                'groom_full_name' => 'Budi Santoso, S.T.',
                'groom_nickname' => 'Budi',
                'groom_father_name' => 'Bambang Santoso',
                'groom_mother_name' => 'Endang Lestari',
                'groom_photo' => null,
                'bride_name' => 'Siti',
                'bride_full_name' => 'Siti Nurhaliza, S.E.',
                'bride_nickname' => 'Siti',
                'bride_father_name' => 'Ahmad Hidayat',
                'bride_mother_name' => 'Rahmawati',
                'bride_photo' => null,
            ]
        );

        // 4. Create events
        WeddingEvent::updateOrCreate(
            [
                'wedding_id' => $wedding->id,
                'type' => 'akad',
            ],
            [
                'title' => 'Akad Nikah',
                'event_date' => now()->addDays(30)->toDateString(),
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'venue' => 'Masjid Agung Al-Barkah',
                'address' => 'Jl. Veteran No. 12, Jakarta Pusat',
                'maps_url' => 'https://maps.google.com',
                'description' => 'Mohon hadir tepat waktu dan tetap mematuhi tata tertib tempat ibadah.',
            ]
        );

        WeddingEvent::updateOrCreate(
            [
                'wedding_id' => $wedding->id,
                'type' => 'resepsi',
            ],
            [
                'title' => 'Resepsi Pernikahan',
                'event_date' => now()->addDays(30)->toDateString(),
                'start_time' => '11:00:00',
                'end_time' => '14:00:00',
                'venue' => 'Grand Ballroom Hotel Mulia',
                'address' => 'Jl. Asia Afrika No. 1, Senayan, Jakarta',
                'maps_url' => 'https://maps.google.com',
                'description' => 'Dress code: Nuansa Pastel / Formal Batik.',
            ]
        );

        // 5. Wedding Settings
        WeddingSetting::updateOrCreate(
            ['wedding_id' => $wedding->id],
            [
                'opening_text' => 'Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang. (QS. Ar-Rum: 21)',
                'closing_text' => 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kedua mempelai.',
                'music_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
                'background_image' => null,
                'primary_color' => '#85586F',
                'secondary_color' => '#D0B8A8',
                'font_family' => 'Playfair Display',
                'show_gift' => true,
                'show_rsvp' => true,
                'show_guestbook' => true,
            ]
        );

        // 6. Galleries
        $sampleGalleries = [
            ['image_path' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80', 'caption' => 'Momen Pertunangan', 'sort_order' => 1],
            ['image_path' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=800&q=80', 'caption' => 'Pre-wedding Shoot', 'sort_order' => 2],
            ['image_path' => 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=800&q=80', 'caption' => 'Bersama Menatap Masa Depan', 'sort_order' => 3],
        ];

        foreach ($sampleGalleries as $gal) {
            Gallery::firstOrCreate(
                ['wedding_id' => $wedding->id, 'sort_order' => $gal['sort_order']],
                $gal
            );
        }

        // 7. Guests
        $guests = [
            ['name' => 'Andi Saputra', 'whatsapp_number' => '6281299990001', 'slug' => 'andi-saputra', 'status' => 'opened'],
            ['name' => 'Citra Lestari', 'whatsapp_number' => '6281299990002', 'slug' => 'citra-lestari', 'status' => 'confirmed'],
            ['name' => 'Rizky Ramadhan', 'whatsapp_number' => '6281299990003', 'slug' => 'rizky-ramadhan', 'status' => 'sent'],
        ];

        foreach ($guests as $g) {
            $guest = Guest::updateOrCreate(
                ['wedding_id' => $wedding->id, 'slug' => $g['slug']],
                $g
            );

            // 8. Sample RSVP & Wishes for confirmed guest
            if ($guest->slug === 'citra-lestari') {
                Rsvp::updateOrCreate(
                    ['wedding_id' => $wedding->id, 'guest_id' => $guest->id],
                    [
                        'name' => $guest->name,
                        'attendance' => 'attending',
                        'guest_count' => 2,
                        'message' => 'Selamat menempuh hidup baru Budi & Siti! Semoga selalu sakinah mawaddah warahmah hingga akhir hayat.',
                        'ip_address' => '127.0.0.1',
                    ]
                );
            }
        }

        // Add an extra general guestbook wish
        Rsvp::firstOrCreate(
            ['wedding_id' => $wedding->id, 'name' => 'Keluarga Besar Alumni Teknik'],
            [
                'attendance' => 'attending',
                'guest_count' => 5,
                'message' => 'Happy wedding Mas Budi & Mbak Siti! Lancar sampai hari H yaa!',
                'ip_address' => '127.0.0.1',
            ]
        );

        // 9. Love Stories
        $stories = [
            ['year_or_date' => '2020', 'title' => 'Pertemuan Pertama', 'story' => 'Bertemu saat kegiatan sosial mahasiswa dan mulai saling mengenal satu sama lain.', 'sort_order' => 1],
            ['year_or_date' => '2023', 'title' => 'Komitmen Bersama', 'story' => 'Menyatakan niat serius dan saling mengenalkan keluarga besar masing-masing.', 'sort_order' => 2],
            ['year_or_date' => '2026', 'title' => 'Menuju Pelaminan', 'story' => 'Mengikat janji suci pernikahan untuk mengarungi bahtera rumah tangga selamanya.', 'sort_order' => 3],
        ];

        foreach ($stories as $story) {
            LoveStory::firstOrCreate(
                ['wedding_id' => $wedding->id, 'sort_order' => $story['sort_order']],
                $story
            );
        }

        // 10. Wedding Gifts
        $gifts = [
            ['bank_or_wallet_name' => 'BCA', 'account_number' => '1234567890', 'account_holder_name' => 'Budi Santoso'],
            ['bank_or_wallet_name' => 'Bank Mandiri', 'account_number' => '987654321000', 'account_holder_name' => 'Siti Nurhaliza'],
        ];

        foreach ($gifts as $gift) {
            WeddingGift::firstOrCreate(
                ['wedding_id' => $wedding->id, 'bank_or_wallet_name' => $gift['bank_or_wallet_name']],
                $gift
            );
        }

        // Also create a separate wedding for User B to facilitate Multi-Tenancy testing
        $weddingB = Wedding::updateOrCreate(
            ['slug' => 'andi-diana'],
            [
                'user_id' => $userB->id,
                'template_id' => $template->id,
                'title' => 'Pernikahan Andi & Diana',
                'status' => 'draft',
                'published_at' => null,
            ]
        );

        Couple::updateOrCreate(
            ['wedding_id' => $weddingB->id],
            [
                'groom_name' => 'Andi',
                'groom_full_name' => 'Andi Pratama, S.Kom.',
                'bride_name' => 'Diana',
                'bride_full_name' => 'Diana Putri, S.Pd.',
            ]
        );
    }
}
