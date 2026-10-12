<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wedding;
use App\Services\TemplateRendererService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicInvitationController extends Controller
{
    /**
     * Display a public general wedding invitation by slug.
     */
    public function show(string $slug, TemplateRendererService $renderer): View
    {
        // Strictly fetch published wedding
        $wedding = Wedding::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return $renderer->render($wedding);
    }

    /**
     * Display a personalized wedding invitation for a specific guest.
     */
    public function showPersonalized(string $slug, string $guestSlug, TemplateRendererService $renderer): View
    {
        // Strictly fetch published wedding
        $wedding = Wedding::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Strictly fetch guest belonging to this wedding
        $guest = Guest::where('wedding_id', $wedding->id)
            ->where('slug', $guestSlug)
            ->firstOrFail();

        // Update guest status to 'opened' if it was pending or sent
        if (in_array($guest->status, ['pending', 'sent'])) {
            $guest->update(['status' => 'opened']);
        }

        return $renderer->render($wedding, $guest);
    }
}
