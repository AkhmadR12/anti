<?php

namespace Tests\Feature;

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
use App\Services\WhatsApp\WhatsAppNormalizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StepTwoArchitectureTest extends TestCase
{
    use RefreshDatabase;

    public function test_whatsapp_normalizer_formats_properly(): void
    {
        $this->assertEquals('6281234567890', WhatsAppNormalizer::normalize('081234567890'));
        $this->assertEquals('6281234567890', WhatsAppNormalizer::normalize('+6281234567890'));
        $this->assertEquals('6281234567890', WhatsAppNormalizer::normalize('0812-3456-7890'));
        $this->assertEquals('6281234567890', WhatsAppNormalizer::normalize('  0812 3456 7890  '));
        $this->assertTrue(WhatsAppNormalizer::isValid('081234567890'));
    }

    public function test_user_wedding_relationships_and_multi_tenancy_isolation(): void
    {
        $userA = User::factory()->create(['name' => 'User A', 'email' => 'usera@example.com']);
        $userB = User::factory()->create(['name' => 'User B', 'email' => 'userb@example.com']);

        $template = Template::create([
            'name' => 'Test Template',
            'slug' => 'test-template',
            'thumbnail' => '/thumb.jpg',
            'view_path' => 'test',
            'is_active' => true,
        ]);

        $weddingA = Wedding::create([
            'user_id' => $userA->id,
            'template_id' => $template->id,
            'title' => 'Wedding User A',
            'slug' => 'wedding-user-a',
            'status' => 'published',
        ]);

        $weddingB = Wedding::create([
            'user_id' => $userB->id,
            'template_id' => $template->id,
            'title' => 'Wedding User B',
            'slug' => 'wedding-user-b',
            'status' => 'draft',
        ]);

        // Assert ownership scoping
        $this->assertTrue($userA->weddings->contains($weddingA));
        $this->assertFalse($userA->weddings->contains($weddingB));
        $this->assertTrue($userB->weddings->contains($weddingB));
        $this->assertFalse($userB->weddings->contains($weddingA));

        // Create couple
        $couple = Couple::create([
            'wedding_id' => $weddingA->id,
            'groom_name' => 'Budi',
            'groom_full_name' => 'Budi Santoso',
            'bride_name' => 'Siti',
            'bride_full_name' => 'Siti Nurhaliza',
        ]);
        $this->assertEquals($couple->id, $weddingA->couple->id);

        // Create events
        $event = WeddingEvent::create([
            'wedding_id' => $weddingA->id,
            'type' => 'akad',
            'title' => 'Akad Nikah',
            'event_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'venue' => 'Masjid Raya',
            'address' => 'Jl. Merdeka No. 1',
        ]);
        $this->assertEquals(1, $weddingA->events->count());

        // Create settings
        $setting = WeddingSetting::create([
            'wedding_id' => $weddingA->id,
            'primary_color' => '#4F46E5',
        ]);
        $this->assertEquals('#4F46E5', $weddingA->settings->primary_color);

        // Create gallery
        $gallery = Gallery::create([
            'wedding_id' => $weddingA->id,
            'image_path' => '/img/photo1.jpg',
            'sort_order' => 1,
        ]);
        $this->assertEquals(1, $weddingA->galleries->count());

        // Create guest
        $guest = Guest::create([
            'wedding_id' => $weddingA->id,
            'name' => 'Tamu Kehormatan',
            'slug' => 'tamu-kehormatan',
            'whatsapp_number' => '628111222333',
        ]);
        $this->assertEquals(1, $weddingA->guests->count());

        // Create RSVP
        $rsvp = Rsvp::create([
            'wedding_id' => $weddingA->id,
            'guest_id' => $guest->id,
            'name' => 'Tamu Kehormatan',
            'attendance' => 'attending',
            'guest_count' => 2,
            'message' => 'Selamat berbahagia!',
        ]);
        $this->assertEquals(1, $weddingA->rsvps->count());
        $this->assertEquals($rsvp->id, $guest->rsvp->id);
    }
}
