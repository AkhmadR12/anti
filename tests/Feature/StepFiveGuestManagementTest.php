<?php

namespace Tests\Feature;

use App\Models\Couple;
use App\Models\Guest;
use App\Models\Template;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use App\Models\WeddingSetting;
use App\Services\Contracts\WhatsAppServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StepFiveGuestManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
    protected User $userB;
    protected Template $template;
    protected Wedding $publishedWedding;
    protected Wedding $draftWedding;
    protected Guest $guestA;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userA = User::factory()->create(['name' => 'User A', 'email' => 'usera@example.com']);
        $this->userB = User::factory()->create(['name' => 'User B', 'email' => 'userb@example.com']);

        $this->template = Template::create([
            'name' => 'Elegant Romance',
            'slug' => 'elegant',
            'thumbnail' => '/thumb1.jpg',
            'view_path' => 'elegant',
            'is_active' => true,
        ]);

        // Published Wedding for User A
        $this->publishedWedding = Wedding::create([
            'user_id' => $this->userA->id,
            'template_id' => $this->template->id,
            'title' => 'Pernikahan Budi & Siti',
            'slug' => 'budi-siti',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Couple::create([
            'wedding_id' => $this->publishedWedding->id,
            'groom_name' => 'Budi',
            'groom_full_name' => 'Budi Santoso',
            'bride_name' => 'Siti',
            'bride_full_name' => 'Siti Nurhaliza',
        ]);

        WeddingEvent::create([
            'wedding_id' => $this->publishedWedding->id,
            'type' => 'akad',
            'title' => 'Akad Nikah',
            'event_date' => now()->addDays(14)->toDateString(),
            'start_time' => '08:00',
            'venue' => 'Masjid Istiqlal',
            'address' => 'Jakarta Pusat',
        ]);

        WeddingSetting::create([
            'wedding_id' => $this->publishedWedding->id,
            'opening_text' => 'Salam hangat dan doa restu',
        ]);

        // Sample Guest for Published Wedding
        $this->guestA = Guest::create([
            'wedding_id' => $this->publishedWedding->id,
            'name' => 'Andi Saputra',
            'whatsapp_number' => '6281234567890',
            'slug' => 'andi-saputra',
            'status' => 'pending',
        ]);

        // Draft Wedding for User B
        $this->draftWedding = Wedding::create([
            'user_id' => $this->userB->id,
            'template_id' => $this->template->id,
            'title' => 'Pernikahan Rahasia',
            'slug' => 'pernikahan-rahasia',
            'status' => 'draft',
        ]);

        Couple::create([
            'wedding_id' => $this->draftWedding->id,
            'groom_name' => 'John',
            'groom_full_name' => 'John Doe',
            'bride_name' => 'Jane',
            'bride_full_name' => 'Jane Doe',
        ]);

        WeddingSetting::create([
            'wedding_id' => $this->draftWedding->id,
        ]);
    }

    public function test_public_can_view_published_invitation(): void
    {
        $response = $this->get('/invitation/budi-siti');

        $response->assertStatus(200);
        $response->assertSee('Budi');
        $response->assertSee('Siti');
        $response->assertSee('Masjid Istiqlal');
    }

    public function test_public_cannot_view_draft_invitation(): void
    {
        $response = $this->get('/invitation/pernikahan-rahasia');

        $response->assertStatus(404);
    }

    public function test_personalized_invitation_displays_guest_name_and_updates_status_to_opened(): void
    {
        $this->assertEquals('pending', $this->guestA->status);

        $response = $this->get('/invitation/budi-siti/andi-saputra');

        $response->assertStatus(200);
        $response->assertSee('Andi Saputra');
        $this->assertEquals('opened', $this->guestA->fresh()->status);
    }

    public function test_personalized_invitation_with_invalid_guest_returns_404(): void
    {
        $response = $this->get('/invitation/budi-siti/non-existent-guest');

        $response->assertStatus(404);
    }

    public function test_owner_can_access_guest_management_and_create_guest(): void
    {
        $response = $this->actingAs($this->userA)->get("/weddings/{$this->publishedWedding->id}/guests");

        $response->assertStatus(200);
        $response->assertSee('Andi Saputra');

        // Create new guest
        $createResponse = $this->actingAs($this->userA)->post("/weddings/{$this->publishedWedding->id}/guests", [
            'name' => 'Citra Lestari',
            'whatsapp_number' => '0812-9988-7766',
        ]);

        $createResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('guests', [
            'wedding_id' => $this->publishedWedding->id,
            'name' => 'Citra Lestari',
            'slug' => 'citra-lestari',
            'whatsapp_number' => '6281299887766', // verified normalization
            'status' => 'pending',
        ]);
    }

    public function test_user_cannot_manage_another_users_guests(): void
    {
        // User B tries to view User A's guest management -> 403 Forbidden
        $response = $this->actingAs($this->userB)->get("/weddings/{$this->publishedWedding->id}/guests");
        $response->assertStatus(403);

        // User B tries to add guest to User A's wedding -> 403 Forbidden
        $addResponse = $this->actingAs($this->userB)->post("/weddings/{$this->publishedWedding->id}/guests", [
            'name' => 'Hacker Tamu',
            'whatsapp_number' => '081234567890',
        ]);
        $addResponse->assertStatus(403);

        // User B tries to delete User A's guest -> 403 Forbidden
        $deleteResponse = $this->actingAs($this->userB)->delete("/weddings/{$this->publishedWedding->id}/guests/{$this->guestA->id}");
        $deleteResponse->assertStatus(403);
    }

    public function test_owner_can_update_and_delete_guest(): void
    {
        // Update
        $updateResponse = $this->actingAs($this->userA)->put("/weddings/{$this->publishedWedding->id}/guests/{$this->guestA->id}", [
            'name' => 'Andi Saputra, S.Kom.',
            'whatsapp_number' => '081234567890',
            'status' => 'confirmed',
        ]);

        $updateResponse->assertSessionHasNoErrors();
        $this->assertEquals('confirmed', $this->guestA->fresh()->status);
        $this->assertEquals('Andi Saputra, S.Kom.', $this->guestA->fresh()->name);

        // Delete
        $deleteResponse = $this->actingAs($this->userA)->delete("/weddings/{$this->publishedWedding->id}/guests/{$this->guestA->id}");
        $deleteResponse->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('guests', ['id' => $this->guestA->id]);
    }

    public function test_owner_can_import_guests_via_csv(): void
    {
        $csvContent = "Nama,WhatsApp\n"
            . "Rudi Hartono,081233445566\n"
            . "Maya Indah,085566778899\n"
            . "Keluarga Besar Solo,\n";

        $file = UploadedFile::fake()->createWithContent('guests.csv', $csvContent);

        $response = $this->actingAs($this->userA)->post("/weddings/{$this->publishedWedding->id}/guests/import", [
            'file' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('guests', [
            'wedding_id' => $this->publishedWedding->id,
            'name' => 'Rudi Hartono',
            'whatsapp_number' => '6281233445566',
        ]);
        $this->assertDatabaseHas('guests', [
            'wedding_id' => $this->publishedWedding->id,
            'name' => 'Maya Indah',
            'whatsapp_number' => '6285566778899',
        ]);
        $this->assertDatabaseHas('guests', [
            'wedding_id' => $this->publishedWedding->id,
            'name' => 'Keluarga Besar Solo',
        ]);
    }

    public function test_whatsapp_service_generates_valid_share_link(): void
    {
        $service = app(WhatsAppServiceInterface::class);
        $shareUrl = $service->generateShareUrl($this->guestA, $this->publishedWedding);

        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $shareUrl);
        $this->assertStringContainsString('Andi%20Saputra', $shareUrl);
        $this->assertStringContainsString('budi-siti%2Fandi-saputra', $shareUrl);
    }
}
