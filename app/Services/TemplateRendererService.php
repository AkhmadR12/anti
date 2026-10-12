<?php

namespace App\Services;

use App\Models\Guest;
use App\Models\Wedding;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewResult;

class TemplateRendererService
{
    /**
     * Render the invitation view using the template assigned to the wedding.
     */
    public function render(Wedding $wedding, ?Guest $guest = null, bool $isPreview = false): ViewResult
    {
        // Eager load all relations required by templates
        $wedding->loadMissing([
            'template',
            'couple',
            'events',
            'galleries',
            'settings',
            'loveStories',
            'gifts',
            'rsvps' => fn($q) => $q->latest()->take(30),
        ]);

        $templatePath = $wedding->template->view_path ?? 'elegant';
        $viewName = "templates.{$templatePath}.invitation";

        // Fallback to elegant if specific template view not found
        if (!View::exists($viewName)) {
            $viewName = 'templates.elegant.invitation';
        }

        // Determine primary event date for countdown
        $mainEvent = $wedding->events->first();
        $targetDate = $mainEvent ? $mainEvent->event_date->format('Y-m-d') . ' ' . $mainEvent->start_time : now()->addMonth()->format('Y-m-d H:i:s');

        return view($viewName, [
            'wedding' => $wedding,
            'couple' => $wedding->couple,
            'events' => $wedding->events,
            'galleries' => $wedding->galleries,
            'settings' => $wedding->settings,
            'loveStories' => $wedding->loveStories,
            'gifts' => $wedding->gifts,
            'rsvps' => $wedding->rsvps,
            'guest' => $guest,
            'targetDate' => $targetDate,
            'isPreview' => $isPreview,
        ]);
    }
}
