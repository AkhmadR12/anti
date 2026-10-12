<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeddingEventRequest;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class WeddingEventController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created event for the wedding.
     */
    public function store(StoreWeddingEventRequest $request, Wedding $wedding): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        $wedding->events()->create($request->validated());

        return back()->with('status', 'Sesi acara berhasil ditambahkan.');
    }

    /**
     * Update the specified event.
     */
    public function update(StoreWeddingEventRequest $request, Wedding $wedding, WeddingEvent $event): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        // Ensure this event belongs to the wedding
        abort_if($event->wedding_id !== $wedding->id, 403, 'Akses sesi acara tidak sah.');

        $event->update($request->validated());

        return back()->with('status', 'Sesi acara berhasil diperbarui.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Wedding $wedding, WeddingEvent $event): RedirectResponse
    {
        $this->authorize('manage', $wedding);

        abort_if($event->wedding_id !== $wedding->id, 403, 'Akses sesi acara tidak sah.');

        $event->delete();

        return back()->with('status', 'Sesi acara berhasil dihapus.');
    }
}
