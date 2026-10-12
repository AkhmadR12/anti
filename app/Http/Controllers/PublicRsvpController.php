<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wedding;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PublicRsvpController extends Controller
{
    /**
     * Store public guest RSVP and message with anti-spam protections.
     */
    public function store(Request $request, string $slug): RedirectResponse|JsonResponse
    {
        // 1. Honeypot check: If the hidden honeypot field is filled, silently ignore or reject
        if ($request->filled('honeypot_field')) {
            return back()->with('status', 'Konfirmasi Anda telah diterima.');
        }

        // 2. Strictly fetch published wedding
        $wedding = Wedding::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // 3. Validation
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'attendance' => ['required', 'in:attending,not_attending,tentative'],
            'guest_count' => ['required', 'integer', 'min:1', 'max:10'],
            'message' => ['nullable', 'string', 'max:1000'],
            'guest_id' => ['nullable', 'integer'],
        ]);

        // 4. Sanitize message & name
        $name = strip_tags(trim($validated['name']));
        $message = !empty($validated['message']) ? strip_tags(trim($validated['message'])) : null;

        // 5. Verify guest belongs to this wedding if provided
        $guestId = null;
        if (!empty($validated['guest_id'])) {
            $guest = Guest::where('id', $validated['guest_id'])
                ->where('wedding_id', $wedding->id)
                ->first();

            if ($guest) {
                $guestId = $guest->id;
                // Transition status to 'confirmed'
                $guest->update(['status' => 'confirmed']);
            }
        }

        // 6. Record the RSVP
        $rsvp = $wedding->rsvps()->create([
            'guest_id' => $guestId,
            'name' => $name,
            'attendance' => $validated['attendance'],
            'guest_count' => (int) $validated['guest_count'],
            'message' => $message,
            'ip_address' => $request->ip(),
        ]);

        $successMsg = 'Terima kasih atas konfirmasi kehadiran dan doa restu Anda!';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'rsvp' => $rsvp,
            ]);
        }

        return back()->with('status', $successMsg);
    }
}
