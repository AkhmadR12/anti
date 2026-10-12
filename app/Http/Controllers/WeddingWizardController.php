<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeddingWizardRequest;
use App\Models\Template;
use App\Services\ImageStorageService;
use App\Services\SlugGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WeddingWizardController extends Controller
{
    /**
     * Show the step-by-step wedding creation wizard.
     */
    public function create(): View
    {
        $templates = Template::where('is_active', true)->get();

        return view('weddings.wizard', compact('templates'));
    }

    /**
     * Store a newly created wedding and its associated entities.
     */
    public function store(
        StoreWeddingWizardRequest $request,
        SlugGeneratorService $slugService,
        ImageStorageService $storageService
    ): RedirectResponse {
        $validated = $request->validated();
        $user = $request->user();

        // 1. Generate unique slug
        $slug = $slugService->generateForWedding($validated['title']);

        DB::transaction(function () use ($validated, $user, $slug, $request, $storageService) {
            // 2. Create Wedding
            $wedding = $user->weddings()->create([
                'template_id' => $validated['template_id'],
                'title' => $validated['title'],
                'slug' => $slug,
                'status' => 'draft',
            ]);

            // 3. Handle Couple Photos
            $groomPhoto = null;
            if ($request->hasFile('groom_photo')) {
                $groomPhoto = $storageService->storeImage($request->file('groom_photo'), $wedding->id, 'couple');
            }

            $bridePhoto = null;
            if ($request->hasFile('bride_photo')) {
                $bridePhoto = $storageService->storeImage($request->file('bride_photo'), $wedding->id, 'couple');
            }

            // 4. Create Couple
            $wedding->couple()->create([
                'groom_name' => $validated['groom_name'],
                'groom_full_name' => $validated['groom_full_name'],
                'groom_nickname' => $validated['groom_nickname'] ?? null,
                'groom_father_name' => $validated['groom_father_name'] ?? null,
                'groom_mother_name' => $validated['groom_mother_name'] ?? null,
                'groom_photo' => $groomPhoto,
                'bride_name' => $validated['bride_name'],
                'bride_full_name' => $validated['bride_full_name'],
                'bride_nickname' => $validated['bride_nickname'] ?? null,
                'bride_father_name' => $validated['bride_father_name'] ?? null,
                'bride_mother_name' => $validated['bride_mother_name'] ?? null,
                'bride_photo' => $bridePhoto,
            ]);

            // 5. Create Main Event (Akad Nikah)
            $wedding->events()->create([
                'type' => 'akad',
                'title' => $validated['akad_title'],
                'event_date' => $validated['akad_date'],
                'start_time' => $validated['akad_start_time'],
                'end_time' => $validated['akad_end_time'] ?? null,
                'venue' => $validated['akad_venue'],
                'address' => $validated['akad_address'],
                'maps_url' => $validated['akad_maps_url'] ?? null,
            ]);

            // 6. Create Resepsi Event if provided
            if (!empty($validated['resepsi_venue']) && !empty($validated['resepsi_date'])) {
                $wedding->events()->create([
                    'type' => 'resepsi',
                    'title' => $validated['resepsi_title'] ?? 'Resepsi Pernikahan',
                    'event_date' => $validated['resepsi_date'],
                    'start_time' => $validated['resepsi_start_time'] ?? '11:00',
                    'end_time' => $validated['resepsi_end_time'] ?? null,
                    'venue' => $validated['resepsi_venue'],
                    'address' => $validated['resepsi_address'] ?? $validated['akad_address'],
                    'maps_url' => $validated['resepsi_maps_url'] ?? null,
                ]);
            }

            // 7. Create Settings
            $wedding->settings()->create([
                'opening_text' => $validated['opening_text'] ?? 'Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan.',
                'closing_text' => $validated['closing_text'] ?? 'Merupakan kehormatan bagi kami apabila Anda berkenan hadir dan mendoakan kami.',
                'music_url' => $validated['music_url'] ?? 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
                'primary_color' => $validated['primary_color'] ?? '#4F46E5',
                'secondary_color' => $validated['secondary_color'] ?? '#EC4899',
                'font_family' => $validated['font_family'] ?? 'Playfair Display',
                'show_gift' => true,
                'show_rsvp' => true,
                'show_guestbook' => true,
            ]);

            // 8. Upload initial gallery images if uploaded
            if ($request->hasFile('gallery_images')) {
                $sort = 1;
                foreach ($request->file('gallery_images') as $file) {
                    $path = $storageService->storeImage($file, $wedding->id, 'gallery');
                    $wedding->galleries()->create([
                        'image_path' => $path,
                        'sort_order' => $sort++,
                    ]);
                }
            }
        });

        return redirect()->route('dashboard')->with('status', 'Undangan pernikahan berhasil dibuat! Anda dapat melengkapi detail atau mempublikasikannya.');
    }
}
