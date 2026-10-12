<?php

namespace Tests\Feature;

use App\Models\Couple;
use App\Models\Gallery;
use App\Models\Template;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use App\Models\WeddingSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StepThreeWeddingCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
    protected User $userB;
    protected Template $template;
    protected Wedding $weddingA;
    protected Wedding $weddingB;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->userA = User::factory()->create(['name' => 'User A', 'email' => 'usera@example.com']);
        $this->userB = User::factory()->create(['name' => 'User B', 'email' => 'userb@example.com']);

        $this->template = Template::create([
            'name' => 'Elegant Romance',
            'slug' => 'elegant',
            'thumbnail' => '/thumb.jpg',
            'view_path' => 'elegant',
            'is_active' => true,
        ]);

        $this->weddingA = Wedding::create([
            'user_id' => $this->userA->id,
            'template_id' => $this->template->id,
            'title' => 'Wedding User A',
            'slug' => 'wedding-user-a',
            'status' => 'draft',
        ]);

        Couple::create([
            'wedding_id' => $this->weddingA->id,
            'groom_name' => 'Budi',
            'groom_full_name' => 'Budi Santoso',
            'bride_name' => 'Siti',
            'bride_full_name' => 'Siti Nurhaliza',
        ]);

        WeddingSetting::create([
            'wedding_id' => $this->weddingA->id,
        ]);

        $this->weddingB = Wedding::create([
            'user_id' => $this->userB->id,
            'template_id' => $this->template->id,
            'title' => 'Wedding User B',
            'slug' => 'wedding-user-b',
            'status' => 'published',
        ]);

        Couple::create([
            'wedding_id' => $this->weddingB->id,
            'groom_name' => 'Andi',
            'groom_full_name' => 'Andi Pratama',
            'bride_name' => 'Diana',
            'bride_full_name' => 'Diana Putri',
        ]);

        WeddingSetting::create([
            'wedding_id' => $this->weddingB->id,
        ]);
    }

    public function test_user_can_access_dashboard_and_see_only_their_weddings(): void
    {
        $response = $this->actingAs($this->userA)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Wedding User A');
        $response->assertDontSee('Wedding User B');
    }

    public function test_user_can_access_wizard_screen(): void
    {
        $response = $this->actingAs($this->userA)->get('/weddings/create');

        $response->assertStatus(200);
        $response->assertSee('Wizard Pembuatan Undangan Baru');
        $response->assertSee('Elegant Romance');
    }

    public function test_user_can_submit_wizard_and_create_full_wedding(): void
    {
        $groomPhoto = UploadedFile::fake()->image('groom.jpg');
        $bridePhoto = UploadedFile::fake()->image('bride.jpg');

        $payload = [
            'title' => 'Pernikahan Baru Romeo & Juliet',
            'template_id' => $this->template->id,
            'groom_name' => 'Romeo',
            'groom_full_name' => 'Romeo Montague',
            'groom_photo' => $groomPhoto,
            'bride_name' => 'Juliet',
            'bride_full_name' => 'Juliet Capulet',
            'bride_photo' => $bridePhoto,
            'akad_title' => 'Akad Nikah',
            'akad_date' => now()->addDays(20)->toDateString(),
            'akad_start_time' => '08:00',
            'akad_venue' => 'Gedung Kesenian',
            'akad_address' => 'Jl. Verona No. 1',
            'resepsi_title' => 'Resepsi Akbar',
            'resepsi_date' => now()->addDays(20)->toDateString(),
            'resepsi_start_time' => '11:00',
            'resepsi_venue' => 'Ballroom Verona',
            'resepsi_address' => 'Jl. Verona No. 2',
            'primary_color' => '#85586F',
        ];

        $response = $this->actingAs($this->userA)->post('/weddings', $payload);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('weddings', [
            'user_id' => $this->userA->id,
            'title' => 'Pernikahan Baru Romeo & Juliet',
            'slug' => 'pernikahan-baru-romeo-juliet',
            'status' => 'draft',
        ]);

        $wedding = Wedding::where('slug', 'pernikahan-baru-romeo-juliet')->first();
        $this->assertNotNull($wedding->couple);
        $this->assertEquals('Romeo', $wedding->couple->groom_name);
        $this->assertEquals(2, $wedding->events->count());
        $this->assertNotNull($wedding->settings);
    }

    public function test_multi_tenancy_user_cannot_access_or_edit_another_users_wedding(): void
    {
        // User A tries to open User B's wedding edit page -> MUST 403
        $response = $this->actingAs($this->userA)->get("/weddings/{$this->weddingB->id}/edit");
        $response->assertStatus(403);

        // User A tries to update User B's wedding -> MUST 403
        $response = $this->actingAs($this->userA)->put("/weddings/{$this->weddingB->id}", [
            'title' => 'Hacked Title',
            'slug' => 'hacked-slug',
            'template_id' => $this->template->id,
            'status' => 'draft',
        ]);
        $response->assertStatus(403);

        // User A tries to delete User B's wedding -> MUST 403
        $response = $this->actingAs($this->userA)->delete("/weddings/{$this->weddingB->id}");
        $response->assertStatus(403);

        // User A tries to add event to User B's wedding -> MUST 403
        $response = $this->actingAs($this->userA)->post("/weddings/{$this->weddingB->id}/events", [
            'type' => 'akad',
            'title' => 'Malicious Event',
            'event_date' => now()->toDateString(),
            'start_time' => '10:00',
            'venue' => 'Malicious Venue',
            'address' => 'Malicious Address',
        ]);
        $response->assertStatus(403);
    }

    public function test_owner_can_manage_events(): void
    {
        // Add event
        $response = $this->actingAs($this->userA)->post("/weddings/{$this->weddingA->id}/events", [
            'type' => 'resepsi',
            'title' => 'Resepsi Malam',
            'event_date' => now()->addDays(15)->toDateString(),
            'start_time' => '19:00',
            'venue' => 'Plaza Hall',
            'address' => 'Jl. Sudirman 45',
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('wedding_events', [
            'wedding_id' => $this->weddingA->id,
            'title' => 'Resepsi Malam',
        ]);

        $event = WeddingEvent::where('wedding_id', $this->weddingA->id)->first();

        // Delete event
        $delResponse = $this->actingAs($this->userA)->delete("/weddings/{$this->weddingA->id}/events/{$event->id}");
        $delResponse->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('wedding_events', ['id' => $event->id]);
    }

    public function test_owner_can_upload_and_delete_gallery_images(): void
    {
        $fakeImage = UploadedFile::fake()->image('gallery1.jpg');

        $response = $this->actingAs($this->userA)->post("/weddings/{$this->weddingA->id}/galleries", [
            'images' => [$fakeImage],
            'caption' => 'Foto Romantis 1',
        ]);

        $response->assertSessionHasNoErrors();
        $gallery = Gallery::where('wedding_id', $this->weddingA->id)->first();
        $this->assertNotNull($gallery);
        $this->assertEquals('Foto Romantis 1', $gallery->caption);

        // Delete gallery
        $delResponse = $this->actingAs($this->userA)->delete("/weddings/{$this->weddingA->id}/galleries/{$gallery->id}");
        $delResponse->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('galleries', ['id' => $gallery->id]);
    }

    public function test_owner_can_toggle_publish_status(): void
    {
        $this->assertEquals('draft', $this->weddingA->status);

        $response = $this->actingAs($this->userA)->post("/weddings/{$this->weddingA->id}/publish");
        $response->assertSessionHasNoErrors();

        $this->assertEquals('published', $this->weddingA->fresh()->status);
        $this->assertNotNull($this->weddingA->fresh()->published_at);
    }
}
