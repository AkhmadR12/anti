<?php

namespace Tests\Feature;

use App\Models\Couple;
use App\Models\Template;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use App\Models\WeddingSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StepFourTemplateSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
    protected User $userB;
    protected Template $templateElegant;
    protected Template $templateFloral;
    protected Template $templateMinimalist;
    protected Wedding $weddingA;
    protected Wedding $weddingB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userA = User::factory()->create(['name' => 'User A', 'email' => 'usera@example.com']);
        $this->userB = User::factory()->create(['name' => 'User B', 'email' => 'userb@example.com']);

        $this->templateElegant = Template::create([
            'name' => 'Elegant Romance',
            'slug' => 'elegant',
            'thumbnail' => '/thumb1.jpg',
            'view_path' => 'elegant',
            'is_active' => true,
        ]);

        $this->templateFloral = Template::create([
            'name' => 'Modern Floral',
            'slug' => 'floral',
            'thumbnail' => '/thumb2.jpg',
            'view_path' => 'floral',
            'is_active' => true,
        ]);

        $this->templateMinimalist = Template::create([
            'name' => 'Minimalist Sage',
            'slug' => 'minimalist',
            'thumbnail' => '/thumb3.jpg',
            'view_path' => 'minimalist',
            'is_active' => true,
        ]);

        $this->weddingA = Wedding::create([
            'user_id' => $this->userA->id,
            'template_id' => $this->templateElegant->id,
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

        WeddingEvent::create([
            'wedding_id' => $this->weddingA->id,
            'type' => 'akad',
            'title' => 'Akad Nikah',
            'event_date' => now()->addDays(10)->toDateString(),
            'start_time' => '08:00',
            'venue' => 'Masjid Agung',
            'address' => 'Jl. Veteran',
        ]);

        WeddingSetting::create([
            'wedding_id' => $this->weddingA->id,
            'opening_text' => 'Salam pembuka pernikahan',
        ]);

        $this->weddingB = Wedding::create([
            'user_id' => $this->userB->id,
            'template_id' => $this->templateFloral->id,
            'title' => 'Pernikahan User B',
            'slug' => 'wedding-user-b',
            'status' => 'draft',
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

    public function test_public_can_view_templates_catalog(): void
    {
        $response = $this->get('/templates');

        $response->assertStatus(200);
        $response->assertSee('Elegant Romance');
        $response->assertSee('Modern Floral');
        $response->assertSee('Minimalist Sage');
    }

    public function test_public_can_view_live_demo_for_each_template(): void
    {
        // Demo elegant
        $responseElegant = $this->get('/templates/elegant/demo');
        $responseElegant->assertStatus(200);
        $responseElegant->assertSee('Budi');
        $responseElegant->assertSee('Siti');

        // Demo floral
        $responseFloral = $this->get('/templates/floral/demo');
        $responseFloral->assertStatus(200);
        $responseFloral->assertSee('Budi');
        $responseFloral->assertSee('Siti');

        // Demo minimalist
        $responseMinimalist = $this->get('/templates/minimalist/demo');
        $responseMinimalist->assertStatus(200);
        $responseMinimalist->assertSee('Budi');
        $responseMinimalist->assertSee('Siti');
    }

    public function test_owner_can_preview_their_own_wedding(): void
    {
        $response = $this->actingAs($this->userA)->get("/weddings/{$this->weddingA->id}/preview");

        $response->assertStatus(200);
        $response->assertSee('Mode Preview');
        $response->assertSee('Budi');
        $response->assertSee('Siti');
    }

    public function test_non_owner_cannot_preview_another_users_wedding(): void
    {
        // User A tries to preview User B's wedding -> 403 Forbidden
        $response = $this->actingAs($this->userA)->get("/weddings/{$this->weddingB->id}/preview");

        $response->assertStatus(403);
    }

    public function test_user_can_select_and_apply_template_to_their_wedding(): void
    {
        $this->assertEquals($this->templateElegant->id, $this->weddingA->template_id);

        $response = $this->actingAs($this->userA)->post("/templates/{$this->templateFloral->id}/select", [
            'wedding_id' => $this->weddingA->id,
        ]);

        $response->assertRedirect("/weddings/{$this->weddingA->id}/edit");
        $this->assertEquals($this->templateFloral->id, $this->weddingA->fresh()->template_id);
    }

    public function test_user_cannot_apply_template_to_another_users_wedding(): void
    {
        // User A attempts to alter User B's wedding template
        $response = $this->actingAs($this->userA)->post("/templates/{$this->templateMinimalist->id}/select", [
            'wedding_id' => $this->weddingB->id,
        ]);

        // Scoped findOrFail will throw 404 (not found in user's weddings)
        $response->assertStatus(404);
        $this->assertNotEquals($this->templateMinimalist->id, $this->weddingB->fresh()->template_id);
    }
}
