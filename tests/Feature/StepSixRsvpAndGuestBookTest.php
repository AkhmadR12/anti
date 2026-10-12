<?php

namespace Tests\Feature;

use App\Models\Couple;
use App\Models\Guest;
use App\Models\Rsvp;
use App\Models\Template;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use App\Models\WeddingSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StepSixRsvpAndGuestBookTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
    protected User $userB;
    protected Template $template;
    protected Wedding $weddingA;
    protected Wedding $weddingB;
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

        // Wedding A (Published)
        $this->weddingA = Wedding::create([
            'user_id' => $this->userA->id,
            'template_id' => $this->template->id,
            'title' => 'Pernikahan Budi & Siti',
            'slug' => 'budi-siti',
            'status' => 'published',
            'published_at' => now(),
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

        $this->guestA = Guest::create([
            'wedding_id' => $this->weddingA->id,
            'name' => 'Andi Saputra',
            'slug' => 'andi-saputra',
            'whatsapp_number' => '6281234567890',
            'status' => 'opened',
        ]);

        // Wedding B (User B)
        $this->weddingB = Wedding::create([
            'user_id' => $this->userB->id,
            'template_id' => $this->template->id,
            'title' => 'Pernikahan User B',
            'slug' => 'wedding-user-b',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Couple::create([
            'wedding_id' => $this->weddingB->id,
            'groom_name' => 'Pria B',
            'groom_full_name' => 'Pria Budi',
            'bride_name' => 'Wanita B',
            'bride_full_name' => 'Wanita Bella',
        ]);

        WeddingSetting::create([
            'wedding_id' => $this->weddingB->id,
        ]);
    }

    public function test_guest_can_submit_rsvp_and_wishes(): void
    {
        $payload = [
            'name' => 'Sahabat SMA Budi',
            'attendance' => 'attending',
            'guest_count' => 2,
            'message' => 'Selamat berbahagia, semoga langgeng sampai tua!',
        ];

        $response = $this->post('/invitation/budi-siti/rsvp', $payload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('rsvps', [
            'wedding_id' => $this->weddingA->id,
            'name' => 'Sahabat SMA Budi',
            'attendance' => 'attending',
            'guest_count' => 2,
            'message' => 'Selamat berbahagia, semoga langgeng sampai tua!',
        ]);
    }

    public function test_honeypot_blocks_bot_spam_submission(): void
    {
        $payload = [
            'name' => 'Spam Bot',
            'attendance' => 'attending',
            'guest_count' => 1,
            'message' => 'Buy cheap watches online now!',
            'honeypot_field' => 'bot-filled-content',
        ];

        $response = $this->post('/invitation/budi-siti/rsvp', $payload);

        // Honeypot should prevent saving to database
        $this->assertDatabaseMissing('rsvps', [
            'name' => 'Spam Bot',
        ]);
    }

    public function test_registered_guest_status_updates_to_confirmed_on_rsvp(): void
    {
        $this->assertEquals('opened', $this->guestA->status);

        $payload = [
            'guest_id' => $this->guestA->id,
            'name' => $this->guestA->name,
            'attendance' => 'attending',
            'guest_count' => 2,
            'message' => 'Siap hadir bersama pasangan!',
        ];

        $response = $this->post('/invitation/budi-siti/rsvp', $payload);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('confirmed', $this->guestA->fresh()->status);
    }

    public function test_rsvp_data_isolation_between_different_weddings(): void
    {
        // Add RSVP to Wedding A
        $rsvpA = Rsvp::create([
            'wedding_id' => $this->weddingA->id,
            'name' => 'Tamu Khusus A',
            'attendance' => 'attending',
            'guest_count' => 1,
            'message' => 'Doa untuk Wedding A',
        ]);

        // Add RSVP to Wedding B
        $rsvpB = Rsvp::create([
            'wedding_id' => $this->weddingB->id,
            'name' => 'Tamu Khusus B',
            'attendance' => 'not_attending',
            'guest_count' => 1,
            'message' => 'Doa untuk Wedding B',
        ]);

        $this->assertTrue($this->weddingA->rsvps->contains($rsvpA));
        $this->assertFalse($this->weddingA->rsvps->contains($rsvpB));
        $this->assertTrue($this->weddingB->rsvps->contains($rsvpB));
        $this->assertFalse($this->weddingB->rsvps->contains($rsvpA));
    }

    public function test_owner_can_access_rsvp_dashboard_and_delete_entry(): void
    {
        $rsvp = Rsvp::create([
            'wedding_id' => $this->weddingA->id,
            'name' => 'Tamu Uji Coba',
            'attendance' => 'attending',
            'guest_count' => 3,
            'message' => 'Pesan uji coba',
        ]);

        $response = $this->actingAs($this->userA)->get("/weddings/{$this->weddingA->id}/rsvps");

        $response->assertStatus(200);
        $response->assertSee('Tamu Uji Coba');
        $response->assertSee('3 Pax');

        // Delete RSVP
        $delResponse = $this->actingAs($this->userA)->delete("/weddings/{$this->weddingA->id}/rsvps/{$rsvp->id}");
        $delResponse->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('rsvps', ['id' => $rsvp->id]);
    }

    public function test_non_owner_cannot_access_or_delete_another_users_rsvp(): void
    {
        $rsvpA = Rsvp::create([
            'wedding_id' => $this->weddingA->id,
            'name' => 'Tamu Asli A',
            'attendance' => 'attending',
            'guest_count' => 1,
        ]);

        // User B tries to view Wedding A's RSVP dashboard -> 403 Forbidden
        $response = $this->actingAs($this->userB)->get("/weddings/{$this->weddingA->id}/rsvps");
        $response->assertStatus(403);

        // User B tries to delete Wedding A's RSVP -> 403 Forbidden
        $delResponse = $this->actingAs($this->userB)->delete("/weddings/{$this->weddingA->id}/rsvps/{$rsvpA->id}");
        $delResponse->assertStatus(403);
    }

    public function test_owner_can_export_rsvp_to_csv(): void
    {
        Rsvp::create([
            'wedding_id' => $this->weddingA->id,
            'name' => 'Tamu Terdaftar Export',
            'attendance' => 'attending',
            'guest_count' => 2,
            'message' => 'Pesan export',
        ]);

        $response = $this->actingAs($this->userA)->get("/weddings/{$this->weddingA->id}/rsvps/export");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');
    }
}
