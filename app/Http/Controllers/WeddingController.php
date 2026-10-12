<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateWeddingRequest;
use App\Models\Template;
use App\Models\Wedding;
use App\Services\ImageStorageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WeddingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show the wedding management control center.
     */
    public function edit(Wedding $wedding): View
    {
        $this->authorize('view', $wedding);

        $wedding->load([
            'template',
            'couple',
            'events',
            'galleries',
            'settings',
            'guests',
            'rsvps',
        ]);

        $templates = Template::where('is_active', true)->get();

        return view('weddings.edit', compact('wedding', 'templates'));
    }

    /**
     * Preview the invitation for the wedding owner.
     */
    public function preview(Wedding $wedding, \App\Services\TemplateRendererService $renderer): View
    {
        $this->authorize('view', $wedding);

        return $renderer->render($wedding, null, true);
    }

    /**
     * Update wedding master data (Title, slug, template, status).
     */
    public function update(UpdateWeddingRequest $request, Wedding $wedding): RedirectResponse
    {
        $this->authorize('update', $wedding);

        $validated = $request->validated();
        if ($validated['status'] === 'published' && !$wedding->published_at) {
            $validated['published_at'] = now();
        }

        $wedding->update($validated);

        return back()->with('status', 'Informasi utama undangan berhasil diperbarui.');
    }

    /**
     * Toggle wedding status between published and draft.
     */
    public function publish(Request $request, Wedding $wedding): RedirectResponse
    {
        $this->authorize('update', $wedding);

        $newStatus = $wedding->status === 'published' ? 'draft' : 'published';
        $wedding->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? ($wedding->published_at ?? now()) : $wedding->published_at,
        ]);

        $message = $newStatus === 'published' 
            ? 'Selamat! Undangan pernikahan berhasil dipublikasikan.' 
            : 'Status undangan diubah kembali menjadi Draft.';

        return back()->with('status', $message);
    }

    /**
     * Delete a wedding and all its uploaded files.
     */
    public function destroy(Request $request, Wedding $wedding): RedirectResponse
    {
        $this->authorize('delete', $wedding);

        // Remove storage directory for this wedding
        $weddingFolder = "weddings/{$wedding->id}";
        if (Storage::disk('public')->exists($weddingFolder)) {
            Storage::disk('public')->deleteDirectory($weddingFolder);
        }

        $wedding->delete();

        return redirect()->route('dashboard')->with('status', 'Undangan pernikahan berhasil dihapus.');
    }
}
